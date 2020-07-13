<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    //指定表名
    protected $table = 'circle';
    //指定主键
    protected $primaryKey = 'circle_id';
    //是否开启时间戳
    public $timestamps = false;
    //设置时间戳格式为Unix
    protected $dateFormat = 'U';
    //过滤字段，只有包含的字段才能被更新
    protected $fillable = ['circle_name','circle_img','circle_des','user_id','created_time'];
    //隐藏字段
    protected $hidden = [];

    public function circleGet($circle_id){
        return $this->find($circle_id);
    }
    public function circleAdd($circle_data){
        // $this->fill($circle_data);
        // $this->save();
        return $this->insertGetId($circle_data); 
    }
    public function circleUpdate($circle_id,$circle_data){
        $rows_cnt = $this->where('circle_id','=',$circle_id)
            ->update($circle_data);
        return $rows_cnt;
    }
    //我创建的圈子
    public function myCirlcelList($user_id){
        return $this->where('user_id',$user_id)
        ->get(['circle_id','circle_name','circle_img','circle_des','created_time']);
    }
    
    //我加入的圈子
    public function myJoinCircleList($where_arr,$page,$pagesize){
        $select = new Circle();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->join('circle_user_merge','circle.circle_id','=','circle_user_merge.circle_id')
        // ->where('user_id',$user_id)
        // ->where('is_master','N')
        ->orderByDesc('circle.circle_id')
        ->offset(($page-1)*$pagesize)
        ->limit($pagesize)
        ->get(['circle.user_id','circle.circle_id','circle_name','circle_img','circle_des','circle_user_merge.user_remarks_name','circle.created_time']);
    }
    public function myJoinCircleCnt($where_arr){
        $select = new Circle();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->join('circle_user_merge','circle.circle_id','=','circle_user_merge.circle_id')
        // ->where('user_id',$user_id)
        // ->where('is_master','N')
        ->count();
    }
    
    // 圈子分页列表,这里绑定了创建人
    public function circleList($where_arr,$page,$pagesize){
        $select = new Circle();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->join('circle_user_merge',function ($join) {
                $join->on('circle.circle_id', '=','circle_user_merge.circle_id')
                ->on('circle.user_id', '=','circle_user_merge.user_id');})
                //->join('circle_user_merge','circle.circle_id','=','circle_user_merge.circle_id')
                ->orderByDesc('circle.circle_id')
                ->offset(($page-1)*$pagesize)
                ->limit($pagesize)->get(['circle.user_id','circle.circle_id','circle_name','circle_img','circle_des','circle_user_merge.user_remarks_name','circle.created_time']);
    }
    //圈子总数
    public function circleCnt($where_arr){
        $select = new Circle();
        foreach($where_arr as $k=>$v){
            if(strstr($k,'-like')){
                $k = str_replace('-like','',$k);
                $select = $select->where($k,'like', '%'.$v.'%');
            }else{
                $select = $select->where($k,'=',$v);
            }
        }
        return $select->count();
    }

}
