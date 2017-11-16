<?php
namespace app\index\controller;

class PerInfo extends Base
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

    //查看个人信息
 	public function index(){
 		return $this->staff->hidden(['password','cookie_name'])->toJson();
 	}

 	//修改密码
    public function changepwd($perpwd,$newpwd,$newpwd2=null){
 	    $perpwd = sha1(md5($perpwd));
        if($this->staff['password']!=$perpwd)
            return returnWarning('旧密码错误');

        if(!empty($newpwd2)){
            if($newpwd!==$newpwd2)
                return returnWarning('两次密码不一致');
        }
        //更新密码
        $newpwd = sha1(md5($newpwd));
        $this->staff->password = $newpwd;
        $this->staff->save();
        return returnSuccess('密码修改成功');
    }
}