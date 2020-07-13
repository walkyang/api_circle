<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Utils\WxBizDataCrypt;
use Carbon\Carbon;

class UserController extends BaseController
{
    //用户授权
    public function user_auth(Request $request)
    {
        $wx_code = $request->input('code');
        $iv = $request->input('iv');
        $encryptedData= $request->input('encryptedData');
        $appid = env('WX_APP_ID');
        $secret = env('WX_APP_SECRET');
        $data = '';
        
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$wx_code."&grant_type=authorization_code";
        $jsonResult = file_get_contents($url);
        $resultArray = json_decode($jsonResult, true);
        $sessionKey = $resultArray["session_key"];
        
        $wx = new WxBizDataCrypt($appid, $sessionKey);
        $wx_data = $wx->decryptData($encryptedData, $iv);
        
        if (strstr($wx_data,'|')) {
            $user_arr = explode('|',$wx_data)[1];
            $user_json = json_decode($user_arr, true);
            $addtime = Carbon::now()->toDateTimeString();

            $user = new User();
            $wx_id = $user_json['openId'];
            $user_data['wx_id'] = $wx_id;
            $user_data['wx_nickname'] = $user_json['nickName'];
            $user_data['wx_sex'] = $user_json['gender']; //性别 0：未知、1：男、2：女
            $user_data['wx_city'] = $user_json['city']; //城市
            $user_data['wx_province'] = $user_json['province']; //省份
            $user_data['wx_country'] = $user_json['country']; //国家
            $user_data['wx_photo'] = $user_json['avatarUrl']; //用户图像
            $user_data['created_time'] = $addtime;
            $data = $user->userUpdateOrCreate($wx_id,$user_data);
            return self::returnJson(1000,'获取成功', $data);
        } else {
            return self::returnJson(1001,'出错了',$data);
        }
    }
}
