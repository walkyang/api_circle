<?php

/*常用方法*/
 
//显示方法
function json_result($code,$data){
    $msg = '';
    switch($code){
        case 1000: $msg='成功'; break;
        case 1001: $msg='缺少参数'; break;
        case 1002: $msg='请求超时'; break;
        case 1003: $msg='签名错误'; break;
        case 1004: $msg='系统错误'; break;
        case 1005: $msg='已存在'; break;
        case 1006: $msg='更新成功'; break;
        case 1007: $msg='条数超过限制'; break;
        case 1008: $msg='用户名密码错误'; break;
        case 1009: $msg='权限错误'; break;
        case 2000: $msg='不是合法的请求'; break;
        case 3000: $msg='没权权限访问'; break;
        case 4000: $msg = '支付出错';break;
        case 5000: $msg = '未知错误';break;
    }
    return json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data));
}