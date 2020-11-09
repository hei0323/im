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
    Route::middleware('agent')->group(function (){
        Route::post('/friend/addApply','FriendController@addApply');
        Route::get('/friend/addConfirm','FriendController@addConfirm');
        Route::get('/friend/del','FriendController@del');
        Route::get('/friend/list','FriendController@list');

        Route::post('/group/addApply','GroupController@add');
        Route::get('/group/addConfirm','GroupController@addConfirm');
        Route::get('/group/del','GroupController@del');
        Route::get('/group/list','GroupController@list');

        Route::post('/msg/send','MsgController@send');
        Route::get('/msg/list/save','MsgController@msgListSave');
        Route::get('/msg/list/show','MsgController@msgListShow');

        Route::get('/member','MemberController@index');
        Route::get('/member/online','MemberController@online');
        Route::get('/member/{memberId}','MemberController@show');
    });
    Route::middleware('agent:1')->group(function (){
        Route::get('/auth/bind/{client_id}','AuthController@bind');
    });

});
