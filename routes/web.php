<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//调试控制器路由
Route::namespace('Test')->middleware('web')->group(function (){
    Route::get('/config','ConfigTestController@index');
    Route::get('/redis','RedisTestController@index');
});
