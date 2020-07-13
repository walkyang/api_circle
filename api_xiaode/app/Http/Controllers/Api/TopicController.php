<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Topic;
use App\Models\TopicFile;
use App\Models\TopicUser;
use App\Models\CircleUser;
use Carbon\Carbon;
use App\Utils\WxPushData;

class TopicController extends BaseController
{
    //添加主题
    public function topic_add(Request $request)
    {
        $code = 1000; $msg = "添加成功"; $data = array();
        $content = $request->input('content','');
        $circle_id = $request->input('circle_id',0);
        $user_id = $request->input('user_id',0);

        $add_time = Carbon::now()->toDateTimeString();
        //1：添加topic生成topic_id
        $data_topic=['circle_id'=>$circle_id,'user_id'=>$user_id,'topic_content'=>$content,'created_time'=>$add_time];
        $topic = new Topic();
        $topic_id = $topic->topicAdd($data_topic);
        //2：添加topic图片，文件
        $image_str = $request->input('img_str');
        $image_arr = explode('|',$image_str);
        $image_size_arr =  explode('|',$request->input('img_size'));
        $file_str = $request->input('file_str','');
        $file_arr = explode('|',$file_str);
        $file_name_arr =  explode('|',$request->input('file_name_str'));
        $file_extension_arr =  explode('|',$request->input('file_extension_str'));
        $file_size_arr =  explode('|',$request->input('file_size'));
        $data_topic_file = array();
        if($image_str){
            for($i = 0; $i < count($image_arr); $i ++){
                $img_src_arr = explode('/',$image_arr[$i]);
                $img_name = $img_src_arr[count($img_src_arr)-1];
                $img_name_arr = explode('.',$img_name);
                $img_extension = $img_name_arr[count($img_name_arr)-1];
                $file_data = ['topic_id'=>$topic_id,'file_src'=>$image_arr[$i],'file_name'=>$img_name,
                'file_extension'=>$img_extension,'file_size'=>$image_size_arr[$i],'is_image'=>1,'created_time'=>$add_time];
                $data_topic_file[] = $file_data;
            }
        }
        if($file_str){
            for($i = 0; $i < count($file_arr); $i ++){
                $file_data = ['topic_id'=>$topic_id,'file_src'=>$file_arr[$i],'file_name'=>$file_name_arr[$i],
                'file_extension'=>$file_extension_arr[$i],'file_size'=>$file_size_arr[$i],'is_image'=>2,'created_time'=>$add_time];
                $data_topic_file[] = $file_data;
            }
        }
        if(count($data_topic_file) > 0){
            $topic_file = new TopicFile();
            $topic_file->topicFileAddByBatch($data_topic_file);
        }
        //3：获取给该圈子的所有人
        $circle_user = new CircleUser();
        $circle_user_list = $circle_user->circleUserListAll($circle_id);
        //4：发送给该圈子的所有人
        $topic_user = new TopicUser();
        $data_topic_user_merage = array();
        foreach($circle_user_list as $k=>$v){
            $data_topic_user = ['topic_id'=>$topic_id,'user_id'=>$v->user_id,'created_time'=>$add_time];
            $data_topic_user_merage[] = $data_topic_user;
        }
        if(count($data_topic_user_merage) > 0){
            $topic_user->topicUserAddByBatch($data_topic_user_merage);
        }
        //5：发布给订阅的所有人 ->> 圈子名称，发送人昵称，发送时间，内容
        $circle_user = new CircleUser();
        $data_user_push_list = $circle_user->circleUserListByPush($circle_id);
        $wx_push = new WxPushData();
        $template_id = 'cpIDAdR5MMZJ0myHPMAelLc6nSwLSOwquQ7C68Do44M';
        $page = '/pages/topicdetail/topicdetail?id='.$topic_id;
        $info = $circle_user->getCircleMasterUserInfo($circle_id);
        if($info){
            $send_data = array("name1"=>array("value"=>$info->circle_name),"thing4"=>array("value"=>$info->user_remarks_name),
            "time3"=>array("value"=>$add_time),"thing2"=>array("value"=>$content));
            foreach($data_user_push_list as $k=>$v){
                $wx_push->wxsend($v->wx_id,$template_id,$page,$send_data);
            }
        }
        return self::returnJson($code,$msg,$data);
    }

    //阅读主题
    public function topic_read(Request $request){
        $code = 1000; $msg = "添加成功"; $data = array();

        $user_id = $request->input('user_id',0);
        $topic_id = $request->input('topic_id',0);
        $add_time = Carbon::now()->toDateTimeString();

        $topic_user = new TopicUser();
        $row = $topic_user->topicRead($topic_id,$user_id,$add_time);
        return self::returnJson($code,$msg,$data);
    }

    //我发布的主题
    public function my_topic_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',5);
        $user_id = $request->input('user_id',0);

        $s_where['is_master'] = 'Y';
        if($user_id){
            $s_where['topic_user_merge.user_id'] = $user_id;
        }
        //列表
        $topic_user = new TopicUser();
        $topic_list = $topic_user->topicUserList($s_where,$page,$pagesize);
        //主题图片文件
        $topic_file = new TopicFile();
        foreach($topic_list as $k=>$v){
            $topic_file_list = $topic_file->topicFileList($v->topic_id);
            //1:图片
            $image_list = array();
            $image_cnt = 0;
            //2:文件
            $file_list = array();
            $file_cnt = 0;
            
            foreach($topic_file_list as $k1=>$v1){
                if($v1->is_image == 1){
                    $image_list[] = array('file_name'=>$v1->file_name,'file_src'=>$v1->file_src,
                    'file_extension'=>$v1->file_extension,'file_size'=>$v1->file_size);
                    $image_cnt ++;
                }else{
                    $file_list[] = array('file_name'=>$v1->file_name,'file_src'=>$v1->file_src,
                    'file_extension'=>$v1->file_extension,'file_size'=>$v1->file_size);
                    $file_cnt ++;
                }
            }

            $v['image_list'] = $image_list;
            $v['image_cnt'] = $image_cnt;
            $v['file_list'] = $file_list;
            $v['file_cnt'] = $file_cnt;
            //3：阅读人数
            $v['read_cnt'] = $topic_user->topicReadUserCnt($v->topic_id,'Y');
        }
        //总数
        $topic_total = $topic_user->topicUserCnt($s_where);
        $data=array('total'=>$topic_total,'list'=>$topic_list);
        return self::returnJson($code,$msg,$data);
    }

    //主题详细
    public function topic_detail(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $topic_id = $request->input('topic_id',0);
        $user_id = $request->input('user_id',0);
        $topic = new Topic();
        $data = $topic->topicGet($topic_id);
        //主题图片文件
        $topic_file = new TopicFile();
        
        $topic_file_list = $topic_file->topicFileList($topic_id);
        //1:图片
        $image_list = array();
        $image_cnt = 0;
        //2:文件
        $file_list = array();
        $file_cnt = 0;
            
        foreach($topic_file_list as $k1=>$v1){
            if($v1->is_image == 1){
                $image_list[] = array('file_name'=>$v1->file_name,'file_src'=>$v1->file_src,
                'file_extension'=>$v1->file_extension,'file_size'=>$v1->file_size);
                $image_cnt ++;
            }else{
                $file_list[] = array('file_name'=>$v1->file_name,'file_src'=>$v1->file_src,
                'file_extension'=>$v1->file_extension,'file_size'=>$v1->file_size);
                $file_cnt ++;
            }
        }

        $data['image_list'] = $image_list;
        $data['image_cnt'] = $image_cnt;
        $data['file_list'] = $file_list;
        $data['file_cnt'] = $file_cnt;
        //3：阅读人数
        $topic_user = new TopicUser();
        $data['read_cnt'] = $topic_user->topicReadUserCnt($topic_id,'Y');
        //3：未阅读人数
        $data['noread_cnt'] = $topic_user->topicReadUserCnt($topic_id,'N');
        //4：判断当前用户是否是发布人
        $is_master = 'N';
        if($user_id == $data->user_id){
            $is_master = 'Y';
        }
        $data['is_master'] = $is_master;
        
        return self::returnJson($code,$msg,$data);
    }

    //已读人群
    public function read_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',5);
        $topic_id = $request->input('topic_id',0);

        $s_where['is_read'] = 'Y';
        if($topic_id){
            $s_where['topic_user_merge.topic_id'] = $topic_id;
        }
        //列表
        $topic_user = new TopicUser();
        $topic_list = $topic_user->topicReadUserList($s_where,$page,$pagesize);
        //总数
        $topic_total = $topic_user->topicReadUserCnt($topic_id,'Y');
        $data=array('total'=>$topic_total,'list'=>$topic_list);
        return self::returnJson($code,$msg,$data);
    }

    //未读人群
    public function noread_list(Request $request){
        $code = 1000; $msg = "获取成功"; $data = array();
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',5);
        $topic_id = $request->input('topic_id',0);

        $s_where['is_read'] = 'N';
        if($topic_id){
            $s_where['topic_user_merge.topic_id'] = $topic_id;
        }
        //列表
        $topic_user = new TopicUser();
        $topic_list = $topic_user->topicReadUserList($s_where,$page,$pagesize);
        //总数
        $topic_total = $topic_user->topicReadUserCnt($topic_id,'N');
        $data=array('total'=>$topic_total,'list'=>$topic_list);
        return self::returnJson($code,$msg,$data);
    }
}
