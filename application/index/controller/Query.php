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
    //将查询到的数据数组转换为前端需要的格式(value、label都为值)
    private function arrayHandel(array $arr){
        $list = [];
        foreach ($arr as $value){
            $tmp = ['value'=>$value,'label'=>$value];
            array_push($list,$tmp);
        }
        return $list;
    }

    //将查询到的数据数组转换为前端需要的格式(value为id、label为值)
    private function arrayHandel2(array $arr){
        $list = [];
        foreach ($arr as $key=>$value){
            $tmp = ['value'=>$value,'label'=>$key];
            array_push($list,$tmp);
        }
        return $list;
    }

    //查询某个地区所有的仓库
    public function storehouse($area){
        $storehouse = db('storehouse')->where('area',$area)->column('name');
        $list = $this->arrayHandel($storehouse);
        return json($list);
    }

    //查询某个地区所有的班组
    public function team($area){
        $team = db('team')->where('area',$area)->column('name');
        $list = $this->arrayHandel($team);
        return json($list);
    }

    //查询所有的材料大类
    public function category(){
        $category = db('category')->where(1)->column('category_name');
        $list = $this->arrayHandel($category);
        return json($list);
    }

    //根据材料大类返回材料名称
    public function stuff($category_name){
        $stuff = db('stuff')->where('category_name',$category_name)->column('stuff_name');
        $list = $this->arrayHandel($stuff);
        return json($list);
    }

    //根据材料大类返回材料名称和id
    public function stuffWithId($category_name){
        $stuff = db('stuff')->where('category_name',$category_name)->column('id','stuff_name');
        $list = $this->arrayHandel2($stuff);
        return json($list);
    }

    //查询所有的生产厂商
    public function manufacturer(){
        $manufacturer = db('manufacturer')->where(1)->column('manufacturer');
        $list = $this->arrayHandel($manufacturer);
        return json($list);
    }

    //查询当前管理员所在仓库
    public function userStorehouse(){
        $storehouse = getUser()['storehouse'];
        $list = $this->arrayHandel([$storehouse]);
        return json($list);
    }
}