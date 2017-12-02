<?php
namespace app\index\controller;
use think\Db;

class StuffApply extends Base
{
    public function __construct(){
        parent::__construct();
        //尝试实例化Inventory的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\StuffOutRecord();
            $this->validate = new \app\index\validate\StuffOutRecord();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //用于检验存入stuff_out_record表的数据是否正确
    private function checkData($data){
        //检测材料批次在数据库中是否真的存在
        if(!dataIsExist('inventory','id',$data['inventory_id']))
            return returnWarning('该库存材料不存在!');

        //检测这批材料是否可用
        $enabled = db('inventory')->where('id',$data['inventory_id'])->value('enabled');
        if($enabled!=1)
            return returnWarning('该批库存不可用！');

        //检测仓库在数据库中是否真的存在
        if(!dataIsExist('storehouse','name',$data['storehouse']))
            return returnWarning('申请仓库不存在!');

        //检测选择的仓库与装维是否在同一地区
        $storehouseArea = db('storehouse')->where('name',$data['storehouse'])->value('area');
        if($storehouseArea!=$this->staff->area)
            return returnWarning('只能向你所在的地区仓库申请材料');

        return true;
    }

    //装维申请材料
    public function apply(){
        $json = $_POST['json'];
        //$json = '{"inventory_id":1,"storehouse":"丹棱一库","out_quantity":20,"odd_quantity":7}';
        $data = json_decode($json,true);


        $checkRes = $this->checkData($data);
        if($checkRes!==true) return $checkRes;

        //检测调拨数量是否大于库存数
        $num = db('inventory')->where('id',$data['inventory_id'])->value('quantity');
        if($num<$data['out_quantity'])
            return returnWarning('申请数量大于库存数量！');

        //获取装维人员姓名
        $staffName = $this->staff->name;
        $data['staff'] = $staffName;

        //获取当前日期
        $data['apply_date'] = date('Y-m-d');

        //添加记录到StuffOutRecord模型
        $res = Manage::add($this->model,$this->validate,$data);
        return json($res);
    }

    //查看自己的材料申请
    public function check($curPage=1,$pageInate=5){
        $count = Db::table('stuff_out_record')
            ->where('staff',$this->staff->name)
            ->count();
        $apps = Db::table('stuff_out_record')
            ->where('staff',$this->staff->name)
            ->page($curPage,$pageInate)
            ->select();
        $data = [];
        $pages = ceil($count/$pageInate);
        array_push($data,['pages'=>$pages]);
        array_push($data,['cur_page'=>$curPage]);
        array_push($data,$apps);
        return json($data);
    }

    //检查id
    private function checkId($id){
        $app = Db::table('stuff_out_record')
            ->where('id',$id)
            ->find();
        if(empty($app))
            return returnWarning('该申请不存在');
        if($app['staff']!==$this->staff->name)
            return returnWarning('你不是该申请申请人');
        return $app;
    }

    //取消申请（只能在未确定领取之前取消）
    public function cancel($id){
        $res = $this->checkId($id);
        if(!is_array($res))
            return $res;
        if($res['is_out']==3 || $res['is_out']==5)
            return returnWarning('材料已经发放，无法取消');
        Db::table('stuff_out_record')
            ->where('id',$id)
            ->delete();
        return returnSuccess('申请已取消');
    }

    //修改申请
    public function change(){
        $json = $_POST['json'];
        $data = json_decode($json,true);
        $res = $this->checkId($data['id']);
        if(!is_array($res))
            return $res;
        if($res['is_out']!=0 && $res['is_out']!=2 && $res['is_out']!=4)
            return returnWarning('材料正在审批或已发放，无法修改');

        //检测调拨数量是否大于库存数
        $num = db('inventory')->where('id',$data['inventory_id'])->value('quantity');
        if($num<$data['out_quantity'])
            return returnWarning('申请数量大于库存数量！');
        //提交修改
        if(Db::table('stuff_out_record')->update($data))
            return returnSuccess('修改成功');
        else return returnWarning('没有任何修改或数据不存在');
    }

    //确认接收材料
    public function confirmReceive($id){
        $res = $this->checkId($id);
        if(!is_array($res))
            return $res;
        if($res['is_out']!=3)
            return returnWarning('该申请还未能通过审批');
        //检测调拨数量是否大于库存数
        $num = db('inventory')
            ->where('id',$res['inventory_id'])
            ->value('quantity');
        if($num<$res['out_quantity'])
            return returnWarning('目前申请数量大于库存数量，无法发料！');
        //修改申请状态，添加接收日期
        Db::table('stuff_out_record')
            ->where('id',$id)
            ->update([
                'out_date'=>date('Y-m-d'),
                'is_out'=>5
            ]);
        //修改对应库存表中的库存数量
        Db::table('inventory')
            ->where('id',$res['inventory_id'])
            ->setDec('quantity',$res['out_quantity']);
        return returnSuccess('已确认接收');
    }

    //重新提交申请
    public function reSubmit($id){
        $res = $this->checkId($id);
        if(!is_array($res))
            return $res;
        if($res['is_out']!=2 && $res['is_out']!=4)
            return returnWarning('只有被驳回的申请才能重新提交');

        Db::table('stuff_out_record')
            ->where('id',$id)
            ->update([
                'operator1'=>null,
                'operator2'=>null,
                'apply_date'=>date('Y-m-d'),
                'is_out'=>0
                ]);
        return returnSuccess('已重新申请');
    }
}