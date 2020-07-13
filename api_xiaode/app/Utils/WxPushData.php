<?php
namespace App\Utils;

class WxPushData
{
    public function wxsend($openid,$template_id,$page,$send_data)
    {
        $access_token = $this->get_wxmini_access_token()->access_token;
        if(!empty($access_token)) {
            $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token='.$access_token;  //此处变量插入字符串不能使用{}！！！
            $data = array(
                "touser"=>$openid,
                "template_id"=>$template_id,
                "page"=>$page,
                "data"=>$send_data,
                'miniprogram_state'=>'formal'//跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
            );
            $result = $this->curlPost($url,$data,'json');
            $arr = array('ret'=>1,
                'msg'=>'success',
                'data'=>array('result'=>$result),
            );
        } else {
            $arr = array('ret'=>0,'msg'=>'ACCESS TOKEN为空！');
        }
        return json_encode($arr);
    }

    private function get_wxmini_access_token(){
        $grant_type="client_credential";
        $appid = env('WX_APP_ID');
        $appSecret = env('WX_APP_SECRET');
        $url_get="https://api.weixin.qq.com/cgi-bin/token?grant_type=".$grant_type."&appid=".$appid."&secret=".$appSecret;
        try{
            $data = json_decode($this->curlGet($url_get));
            return $data;
        }
        catch(Exception $e){
            output_error($e->getMessage());
        }
    }

    private function curlPost($url, $data = NULL, $json = false){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
        if(!empty($data)){
            if($json && is_array($data)){
                $data = json_encode( $data );
            }
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
            if($json){ //发送JSON数据
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8','Content-Length:' . strlen($data)));
            }
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        $errorno = curl_errno($ch);
        if ($errorno){
            return array('errorno' => false, 'errmsg' => $errorno);
        }
        curl_close($ch);
        return $res;
    }

    private function curlGet($url,$method='get',$data=''){
        $ch = curl_init();
        $header=array();
        $header[] = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $temp = curl_exec($ch);
        curl_close($ch);
        return $temp;
    }
}
