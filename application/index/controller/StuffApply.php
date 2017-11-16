<?php
namespace app\index\controller;

class StuffApply extends Base
{
    public function __construct(){
        parent::__construct();
        //尝试实例化Inventory的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Staff();
            $this->validate = new \app\index\validate\Staff();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //装维申请材料
    public function apply(){
        $json = $_POST['json'];
        $json = '';
    }
}