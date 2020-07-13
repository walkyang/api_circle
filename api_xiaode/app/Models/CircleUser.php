<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CircleUser extends Model
{
    //指定表名
    protected $table = 'circle_user_merge';
    //指定主键
    protected $primaryKey = 'circle_user_id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['user_id','circle_id','is_master','user_remarks_name','user_is_push','created_time'];
    //隐藏字段
    protected $hidden = [];

    public function circleUserListAll($circle_id){
        return $this->where('circle_id',$circle_id)
        ->get(['user_id','user_remarks_name']);
    }
    public function circleUserAdd($circle_user_data){
        $this->fill($circle_user_data);
        $this->save();
    }
    //批量插入
    public function circleUserAddByBatch($circle_user_data){
        $this->insert($circle_user_data);
    }
    
    //圈子人数
    public function circleUserList($circle_id,$page,$pagesize){
        return $this->join('user','user.user_id','=','circle_user_merge.user_id')
        ->where('circle_user_merge.circle_id',$circle_id)
        ->offset(($page-1)*$pagesize)
        ->limit($pagesize)
        ->orderby('circle_user_merge.created_time','asc')
        ->get(['circle_user_merge.user_id','user_remarks_name','wx_nickname','wx_photo','circle_user_merge.created_time']);
    }
   
    public function circleUserCnt($circle_id){
        return $this->where('circle_id',$circle_id)->count();
    }
    //判断一个用户是否属于该圈子
    public function circleUserJoin($circle_id,$user_id){
        return $this->where('circle_id',$circle_id)
                ->where('user_id',$user_id)
                ->count();
    }
    //获取圈子里面订阅的人openid
    public function circleUserListByPush($circle_id){
        return $this->join('user','user.user_id','=','circle_user_merge.user_id')
        ->where('circle_user_merge.circle_id','=',$circle_id)
        ->where('user_is_push','=','Y')
        ->get(['wx_id']);
    }
    //获取该圈子的名字和发布信息的昵称
    public function getCircleMasterUserInfo($circle_id){
        return $this->join('circle','circle.circle_id','=','circle_user_merge.circle_id')
        ->where('circle_user_merge.circle_id','=',$circle_id)
        ->where('circle_user_merge.is_master','=','Y')
        ->first(['circle_name','user_remarks_name']);
    }
    
}
