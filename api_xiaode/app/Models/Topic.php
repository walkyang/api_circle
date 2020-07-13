<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //指定表名
    protected $table = 'topic';
    //指定主键
    protected $primaryKey = 'topic_id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['topic_content','circle_id','user_id','created_time'];
    //隐藏字段
    protected $hidden = [];

    public function topicGet($topic_id){
        //
        return $this->join('circle','topic.circle_id','=','circle.circle_id')
        ->join('circle_user_merge',function ($join){
            $join->on('topic.user_id','=','circle_user_merge.user_id')
            ->on('topic.circle_id', '=','circle_user_merge.circle_id');
        })
        ->where('topic.topic_id',$topic_id)
        ->where('circle_user_merge.is_master','Y')
        ->first(['topic.user_id','circle_img','circle.circle_id','circle.circle_name','topic.created_time','topic.topic_content','user_remarks_name']);
    }
    public function topicAdd($topic_data){
        //$this->fill($topic_data);
        //$this->save();
        return $this->insertGetId($topic_data); 
    }

    // //首页主题列表
    // public function topicList($where_arr,$page,$pagesize){
    //     $select = new Topic();
    //     foreach($where_arr as $k=>$v){
    //         if(strstr($k,'-like')){
    //             $k = str_replace('-like','',$k);
    //             $select = $select->where($k,'like', '%'.$v.'%');
    //         }else{
    //             $select = $select->where($k,'=',$v);
    //         }
    //     }
    //     // 连接发送表
    //     // 连接圈子表
    //     return $select-->orderByDesc('topic_id')
    //             ->offset(($page-1)*$pagesize)
    //             ->limit($pagesize)
    //             ->get(['user_id','topic_content','circle_id','topic_id','created_time']);
    // }
    // public function topicCnt($where_arr){
    //     $select = new Topic();
    //     foreach($where_arr as $k=>$v){
    //         if(strstr($k,'-like')){
    //             $k = str_replace('-like','',$k);
    //             $select = $select->where($k,'like', '%'.$v.'%');
    //         }else{
    //             $select = $select->where($k,'=',$v);
    //         }
    //     }
    //     return $select->count();
    // }
    
}
