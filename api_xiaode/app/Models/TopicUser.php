<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicUser extends Model
{
    //指定表名
    protected $table = 'topic_user_merge';
    //指定主键
    protected $primaryKey = 'topic_user_id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['topic_id','user_id','is_read','read_time','created_time'];
    //隐藏字段
    protected $hidden = [];

    
    public function topicUserAddByBatch($topic_user_data){
        //$this->fill($topic_user_data);
        //$this->save();
        //return $this->insertGetId($topic_user_data); 
        $this->insert($topic_user_data);
    }

    //首页主题列表
    public function topicUserList($where_arr,$page,$pagesize){
        $select = new TopicUser();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->join('topic','topic_user_merge.topic_id','=','topic.topic_id')// 连接主题表
                ->join('circle','topic.circle_id','=','circle.circle_id')// 连接圈子表
                ->join('circle_user_merge',function ($join) {
                    $join->on('topic.user_id','=','circle_user_merge.user_id')
                    ->on('topic.circle_id', '=','circle_user_merge.circle_id');})//链接用户别名表
                ->offset(($page-1)*$pagesize)
                ->limit($pagesize)
                ->orderby('topic.topic_id','desc')
                ->get(['topic.topic_id', 'circle.circle_img','circle.circle_name','circle.circle_id','circle_user_merge.user_remarks_name',
                'topic.topic_content','topic_user_merge.created_time','is_read','read_time']);
    }
    public function topicUserCnt($where_arr){
        $select = new TopicUser();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->join('topic','topic_user_merge.topic_id','=','topic.topic_id')// 连接主题表
        ->join('circle','topic.circle_id','=','circle.circle_id')// 连接圈子表
        ->join('circle_user_merge',function ($join) {
            $join->on('topic.user_id','=','circle_user_merge.user_id')
            ->on('topic.circle_id', '=','circle_user_merge.circle_id');})//链接用户别名表
        ->count();
    }

    //阅读主题
    public function topicRead($topic_id,$user_id,$read_time){
       $row = $this->where('topic_id',$topic_id)
                ->where('user_id',$user_id)
                ->update(['is_read'=>'Y','read_time'=>$read_time]);
       return $row;
    }
    //已阅读人数,未阅读人数
    public function topicReadUserCnt($topic_id,$is_read){
        return $this->where('topic_id',$topic_id)
            ->where('is_read',$is_read)
            ->count();
    }

    //阅读人数列表，未阅读人数列表
    public function topicReadUserList($where_arr,$page,$pagesize)
    {
        $select = new TopicUser();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->join('topic','topic.topic_id','=','topic_user_merge.topic_id')
            ->join('circle_user_merge',function ($join) {
            $join->on('topic_user_merge.user_id','=','circle_user_merge.user_id')
            ->on('topic.circle_id', '=','circle_user_merge.circle_id');})
            ->join('user','user.user_id','=','circle_user_merge.user_id')
        ->offset(($page-1)*$pagesize)
        ->limit($pagesize)
        ->get(['wx_photo','topic_user_merge.topic_id','circle_user_merge.user_remarks_name','is_read','read_time']);
    }
}
