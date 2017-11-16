<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


//返回success提示和返回信息
function returnSuccess($message){
    return json(['state'=>'success','message'=>$message]);
}

//返回warning提示和错误信息
function returnWarning($message){
    return json(['state'=>'warning','message'=>$message]);
}

//返回error提示和错误信息
function returnError($message){
    return json(['state'=>'error','message'=>$message]);
}
