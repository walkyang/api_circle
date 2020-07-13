<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UploadController extends BaseController
{
    //上传图片
    public function upload_img(Request $request){
        $img = $request->file('image');//图片
        $file_path = date('Ymd');//存储位置--当前日期
        if($img){
            $rule = ['jpg', 'png', 'gif','jpeg'];
            $code = 1000; $path = '';$msg = '';
            $entension = $img->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                $code = 1001;
                $msg = '非法的图片格式';
            }else{
                // 使用 store 存储文件
                $path = $img->store($file_path);//.'/'.date('YmdHis')
                $msg = '上传成功';
            }
            return self::returnJson($code,$msg,'https://'.$request->server('HTTP_HOST').'/upload/'.$path);
        }
    }

    //上传文件
    public function upload_file(Request $request){
        $file = $request->file('file');//文件
        $file_path =  date('Ymd');//存储位置--当前日期
        $file_name = $request->post('filename');//名称
        //return self::returnJson(1000,"测试",$file->getClientOriginalExtension());
        if($file){
            $rule = ['xls','xlsx','doc','docx','pdf','txt','zip'];
            $code = 1000; $path = '';
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                $code = 1001;
                $msg = '非法的文件类型';
            }else{
                // 使用 store 存储文件
                //$path = $file->store($file_path);
                $path = $file->storeAs($file_path, $file_name);
                $msg = '上传成功';
            }
            return self::returnJson($code,$msg,'https://'.$request->server('HTTP_HOST').'/upload/'.$path);
        }

    }
}