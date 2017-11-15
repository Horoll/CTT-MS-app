<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Cookie;
use think\Request;

class Auth extends Controller
{
    //初始化方法，允许ajax跨域
    public function _initialize(){
        //允许ajax跨域
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Origin:http://10.2.130.195:8000');
    }

    //登录，只允许post请求
    public function login($staffname,$password){

        if(!input('?staffname')||!input('?password')) return json(['state'=>'error','message'=>'帐号或密码不存在']);

        //检测帐号和密码
        $password = sha1(md5($password));
        $staff = db('staff')->where('phone',$staffname)->find();
        if(!$staff) return json(['state'=>'error','message'=>'帐号不存在']);
        if($staff['password']!=$password) return json(['state'=>'error','message'=>'密码错误']);

        //生成cookie,并将本次登陆的cookies_name存入数据库
        $cookiestaffname = md5($staffname.time());
        Cookie::set('staffname',$cookiestaffname);
        db('staff')->where('phone',$staffname)->setField('cookie_name',$cookiestaffname);

        //登录成功，将cookiestaffname的值返回前端
        return json(['state'=>'success','message'=>$cookiestaffname]);
    }

    //退出登录
    public function logout(){
        //从cookie中或者请求参数中获取cookiestaffname
        $cookiestaffname = cookie('?staffname')?cookie('staffname'):input('cookie');

        //清空数据库中对应的cookie_name项
        db('staff')->where('cookie_name',$cookiestaffname)->setField('cookie_name',null);
        Cookie::set('staffname',null);

        return json(['state'=>'success','message'=>'注销成功']);
    }
}