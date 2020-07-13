<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TopicUser;
use App\Models\TopicFile;
use Carbon\Carbon;

class HomeController extends BaseController
{
    //首页列表
    public function index(Request $request)
    {
        $code = 1000; $msg = "获取成功"; $data = array();
        $page = $request->input('page',1);
        $pagesize = $request->input('pagesize',5);
        $user_id = $request->input('user_id',0);

        //$s_where['is_master'] = 'Y';
        $s_where = [];
        //if($user_id){
        $s_where['topic_user_merge.user_id'] = $user_id;
        //}
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
}
