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
]);

Route::rule([
    'perinfo' => 'index/PerInfo/index',//查看个人信息
    'changepwd' => 'index/PerInfo/changepwd'//修改密码
]);

Route::rule([
    'stuffapply/apply' => 'index/StuffApply/apply',//材料发放申请
]);

//各种选择的查询
Route::rule([
    'storehousequery' => 'index/Query/storehouse',//仓库查询
    'teamquery' => 'index/Query/team',//班组查询
    'categoryquery' => 'index/Query/category',//材料大类查询
    'stuffquery' => 'index/Query/stuff', //材料名称
    'stuffwithidquery' => 'index/Query/stuffWithId', //材料名称和id
    'manufacturerquery' => 'index/Query/manufacturer', //所有生产商名
    'userstorehousequery' => 'index/Query/userStorehouse', //当前管理员所在仓库
]);