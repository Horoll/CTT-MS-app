<?php
namespace app\index\controller;
use think\Controller;

//除了Auth类，其他控制器类都应继承Base类，用于检测根据cookie返回权限列表
class Base extends Controller
{
    //Model模型对象，派生类应该实例化相应的Model类并赋值给$model
    protected $model;

    //Validate验证器对象，派生类应该实例化相应的Validate类并赋值给$validate
    protected $validate;

    //cookie中的值
    protected $cookieName;

    //当前用户的staff模型对象
    protected $staff;

    //构造方法通过检测cookie的值，判断登录是否非法；若合法，将赋值auth对象给$authList
    public function __construct(){
        parent::__construct();

        //将时区设置为东八区
        date_default_timezone_set('Asia/Chongqing');

        //检测cookie是否存在
        if(!cookie('?staffname') && (input('cookie')=='undefined'||input('cookie')==null))
            die(json_encode(['state'=>'error','message'=>'请先登录'],JSON_UNESCAPED_UNICODE));

        $cookieName = cookie('?staffname')?cookie('staffname'):input('cookie');
        $staffModel = new \app\index\model\Staff();
        $staff = $staffModel->where('cookie_name',$cookieName)->find();
        if(!$staff){
            die(json_encode(['state'=>'error','message'=>'该帐号已在其他地点登录'],JSON_UNESCAPED_UNICODE));
        }
        $this->staff = $staff;
        $this->cookieName = $cookieName;
    }
}