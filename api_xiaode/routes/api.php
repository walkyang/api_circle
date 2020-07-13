<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home/index','Api\HomeController@index');
Route::any('/user/user_auth','Api\UserController@user_auth');
Route::post('/upload/upload_img','Api\UploadController@upload_img');
Route::post('/upload/upload_file','Api\UploadController@upload_file');
Route::get('/wxsend/wxsend','Api\WxSendController@wxsend');

Route::post('/circle/circle_add','Api\CircleController@circle_add');
Route::post('/circle/join_circle','Api\CircleController@join_circle');
Route::get('/circle/circle_list','Api\CircleController@circle_list');
Route::get('/circle/my_circle_list','Api\CircleController@my_circle_list');
Route::get('/circle/join_circle_list','Api\CircleController@join_circle_list');
Route::get('/circle/circle_user_list','Api\CircleController@circle_user_list');

Route::post('/topic/topic_add','Api\TopicController@topic_add');
Route::get('/topic/topic_read','Api\TopicController@topic_read');
Route::get('/topic/my_topic_list','Api\TopicController@my_topic_list');
Route::get('/topic/topic_detail','Api\TopicController@topic_detail');
Route::get('/topic/read_list','Api\TopicController@read_list');
Route::get('/topic/noread_list','Api\TopicController@noread_list');


route::get('/test',function(){
    $s = '';
    $s_arr = explode(',',$s);
    if($s){
        for($i = 0; $i < count($s_arr); $i ++){
            echo 1;
        }
    }
    dd($s_arr);
});