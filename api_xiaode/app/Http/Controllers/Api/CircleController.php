<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Circle;
use App\Models\CircleUser;
use Carbon\Carbon;

class CircleController extends BaseController
{
    //添加圈子
    public function circle_add(Request $request)
    {
        $code = 1000; $msg = "添加成功"; $data = array();
        $circle_name = $request->input('circle_name','');
        $circle_img = $request->input('circle_img','');
        $circle_des = $request->input('circle_des','');
        $user_id = $request->input('user_id',0);
        $user_nickname = $request->input('user_nickname',0);
        $add_time = Carbon::now()->toDateTimeString();
        
        $data_circle=['circle_name'=>$circle_name,'user_id'=>$user_id,'circle_img'=>$circle_img,'circle_des'=>$circle_des,'created_time'=>$add_time];
        $circle = new Circle();
        //新增圈子
        $circle_id = $circle->circleAdd($data_circle);
        //把创建人加入到圈子用户表，并标明身份
        $circle_user = new CircleUser();
        $data_circle_user=['circle_id'=>$circle_id,'user_id'=>$user_id,'is_master'=>'Y','user_remarks_name'=>$user_nickname,'created_time'=>$add_time];
        $circle_user->circleUserAdd($data_circle_user);
        return self::returnJson($code,$msg,$data);
    }

    //圈子列表
    public function circle_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $query = $request->input('query','');
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',5);
        $user_id = $request->input('user_id',0);

        $s_where = [];
        if($query){
            $s_where['circle_name-like'] = $query;
        }
        // if($user_id){
        //     $s_where['circle.user_id'] = $user_id;
        // }
        $circle = new Circle();
        //列表
        $circle_user = new CircleUser();
        $circle_list = $circle->circleList($s_where,$page,$pagesize);
        foreach($circle_list as $k=>$v){
            $circle_user_cnt = $circle_user->circleUserCnt($v->circle_id);
            // 人数
            $v['circle_user_cnt'] = $circle_user_cnt;
            // // 判断用户是否是圈主
            // $is_master = false;
            // if($v->user_id == $user_id){
            //     $is_master = true;
            // }
            // $v['circle_is_master'] = $is_master;
            //判断是否已经加入
            $is_join = 'N';
            $join_cnt = $circle_user->circleUserJoin($v->circle_id,$user_id);
            if($join_cnt > 0){
                $is_join = 'Y';
            }
            $v['is_join'] = $is_join;
            //日期处理
            $v['created_time'] = date('Y-m-d',strtotime($v->created_time));
        }
        //总数
        $circle_total = $circle->circleCnt($s_where);
        $data=array('total'=>$circle_total,'list'=>$circle_list);
        return self::returnJson($code,$msg,$data);
    }

    //我的圈子
    public function my_circle_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $user_id = $request->input('user_id',0);
        $circle = new Circle();
        $data = $circle->myCirlcelList($user_id);
        return self::returnJson($code,$msg,$data);
    }

    //我加入的圈子
    public function join_circle_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $query = $request->input('query','');
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',5);
        $user_id = $request->input('user_id',0);

        $s_where['is_master'] = 'N';
        if($query){
            $s_where['circle_name-like'] = $query;
        }
        //if($user_id){
        $s_where['circle_user_merge.user_id'] = $user_id;
        //}
        $circle = new Circle();
        //列表
        $circle_user = new CircleUser();
        $circle_list = $circle->myJoinCircleList($s_where,$page,$pagesize);
        foreach($circle_list as $k=>$v){
            $circle_user_cnt = $circle_user->circleUserCnt($v->circle_id);
            // 人数
            $v['circle_user_cnt'] = $circle_user_cnt;
        }
        //总数
        $circle_total = $circle->myJoinCircleCnt($s_where);
        $data=array('total'=>$circle_total,'list'=>$circle_list);
        return self::returnJson($code,$msg,$data);
    }

    //加入圈子
    public function join_circle(Request $request){
        $code = 1000; $msg = "加入成功"; $data = array();
        $user_id = $request->input('user_id',0);
        $circle_id = $request->input('circle_id',0);
        $user_nickname = $request->input('user_nickname','');
        $user_remarks_name = $request->input('user_remarks_name','');
        if(!$user_remarks_name){
            $user_remarks_name = $user_nickname;
        }
        $is_push = $request->input('is_push');
        $user_is_push = $is_push ? "Y" : "N";

        $circle_user = new CircleUser();
        $add_time = Carbon::now()->toDateTimeString();
        $circle_user_data = ['user_id'=>$user_id,'circle_id'=>$circle_id,'is_master'=>'N',
        'user_remarks_name'=>$user_remarks_name,'created_time'=>$add_time,'user_is_push'=>$user_is_push];
        $circle_user->circleUserAdd($circle_user_data);
        //修改个人资料，联系方式
        
        return self::returnJson($code,$msg,$data);
    }

    //圈子参加的人列表
    public function circle_user_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',20);
        $user_id = $request->input('user_id',0);
        $circle_id = $request->input('circle_id',0);

        $circle_user = new CircleUser();
        //圈子人员列表
        $circle_user_list = $circle_user->circleUserList($circle_id,$page,$pagesize);
        $circle_user_total = $circle_user->circleUserCnt($circle_id);
        //圈主ID
        $circle = new Circle();
        $circle_info = $circle->circleGet($circle_id);
        $master_user_id = $circle_info->user_id;
        $data=array('total'=>$circle_user_total,'list'=>$circle_user_list,'master_user_id'=>$master_user_id);
        return self::returnJson($code,$msg,$data);
        
    }
}
