<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicFile extends Model
{
    //指定表名
    protected $table = 'topic_file';
    //指定主键
    protected $primaryKey = 'file_id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['topic_id','is_image','file_name','file_src','file_extension','file_size','created_time'];
    //隐藏字段
    protected $hidden = [];

    public function topicFileList($topic_id){
        return $this->where('topic_id',$topic_id)->get();
    }
    public function topicFileAdd($topic_file_data){
        //$this->fill($topic_data);
        //$this->save();
        return $this->insertGetId($topic_file_data); 
    }
    //批量插入
    public function topicFileAddByBatch($topic_file_data){
        $this->insert($topic_file_data);
    }
}
