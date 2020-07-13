<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //指定表名
    protected $table = 'user';
    //指定主键
    protected $primaryKey = 'user_id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['wx_id','wx_nickname','wx_photo','wx_sex','wx_country','wx_province','wx_city','user_mobile','user_qq','created_time'];
    //隐藏字段
    protected $hidden = [];

    public function userGet($user_id){
        return $this->find($user_id);
    }
    public function userAdd($user_data){
        $this->fill($user_data);
        $this->save();
    }
    //更新or创建
    public function userUpdateOrCreate($wx_id,$user_data){
        //这里涉及到添加时间
        //$user = $this->updateOrCreate(['wx_id' => $wx_id], $user_data);
        //return $user;
        $row = $this->where('wx_id',$wx_id)->first();
        if(!$row){
            $user_id = $this->insertGetId($user_data);
        }else{
            $user_id = $row->user_id;
            unset($user_data['created_time']);
            $this->where('user_id','=',$user_id)
            ->update($user_data);
        }
        return $this->find($user_id);
    }
}
