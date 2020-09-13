<?php

/*
|--------------------------------------------------------------------------
| IM 路由组
|--------------------------------------------------------------------------
|
| 这里统一定义即时通讯模块相关路由. 默认统一加载im中间件,支持session及参数绑定模型，去掉了CSRF验证
|
*/

//即时通讯路由
Route::namespace('Chat')->group(function (){
    Route::get('/friend','FriendController@index');
    Route::get('/group','GroupController@index');

    Route::post('/msg/send','MsgController@send');
    Route::get('/msg/list/save','MsgController@msgListSave');
    Route::get('/msg/list/show','MsgController@msgListShow');

    Route::get('/auth/bind/{client_id}','AuthController@bind');

    Route::get('/member','MemberController@index');
    Route::get('/member/online','MemberController@online');
    Route::get('/member/{memberId}','MemberController@show');
});