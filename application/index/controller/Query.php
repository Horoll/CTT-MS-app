<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/11/17
 * Time: 16:18
 */

namespace app\index\controller;
use think\Db;

class Query extends Base
{
    //查找装维人员所在地区
    public function belongArea(){
        return $this->staff->area;
    }

    //查找装维能申请的仓库
    public function avalibleStorehouse(){
        $area = $this->belongArea();
        $storehouses = 
            Db::table('storehouse')
            ->where('area',$area)
            ->column('name');
        return json($storehouses);
    }

    //返回所有材料大类
    public function allCategory(){
        $res = Db::table('category')->where(1)->column('category_name');
        return json($res);
    }

    //根据材料大类返回材料名称和id
    public function stuffName($categoryName){
        $res = Db::table('stuff')->where('category_name',$categoryName)->column('stuff_name','id');

        //逃课来改的bug
        $data = [];
        foreach ($res as $key=>$value){
            $iter = ['id'=>$key,'name'=>$value];
            array_push($data,$iter);
        }
        return json($data);
    }

    //根据仓库名和材料id，返回库存
    public function selectOption($stuffId,$storehouse){
        $res = Db::table('inventory')
            ->where('stuff_id',$stuffId)
            ->where('storehouse',$storehouse)
            ->select();
        return json($res);

    }
}