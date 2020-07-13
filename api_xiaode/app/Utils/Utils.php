<?php
namespace App\Utils;
/*常用方法*/

class Utils
{
    //创建一个随机字符串
    public static function  createNonceStr($length = 8,$lower_char=true) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        if($lower_char){
            $chars .= "abcdefghijklmnopqrstuvwxyz";
        }
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}