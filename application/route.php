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
    'query/belongarea' => 'index/Query/belongArea',//查找装维人员所在地区
    'query/avalibleStorehouse' => 'index/Query/avalibleStorehouse',//能申请材料的仓库（同一地区）
    'query/allcategory' => 'index/Query/allCategory',//返回所有的材料大类
    'query/stuffname' => 'index/Query/stuffname',//根据材料大类返回所有的材料id和名称
    'query/selectoption' => 'index/Query/selectOption',//根据材料id和仓库名称返回所有可选的库存
]);