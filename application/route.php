<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//权限认证Auth类
use think\Route;

Route::rule([
    //logout页面
    'logout' =>  'index/Auth/logout',
    //login页面，只允许post请求
    'login'  =>  ['index/Auth/login',['method'=>'get|post']],
    //权限认证，只允许post方式请求
    'checkauth' =>  ['index/Auth/checkAuth',['method'=>'get|post']],
]);

