<?php
namespace app\index\controller;

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
            return returnWarning('调离仓库不存在!');

        //检测选择的仓库与装维是否在同一地区
        $storehouseArea = db('storehouse')->where('name',$data['storehouse'])->value('area');
        if($storehouseArea!=$this->staff->area)
            return returnWarning('只能向你所在的地区仓库申请材料');

        return true;
    }

    //装维申请材料
    public function apply(){
        $json = $_POST['json'];
        //$json = '{"inventory_id":1,"storehouse":"丹棱一库","out_quantity":20,"staff":"张三","apply_date":"2017-11-17"}';
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

        //添加记录到StuffOutRecord模型
        $res = Manage::add($this->model,$this->validate,$data);
        return json($res);
    }
}