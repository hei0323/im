<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\AliyunOss;
use App\Repositories\Eloquent\UpyunOss;
use App\Repositorys\Contracts\OssInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisTestController extends Controller
{
    //
    public function index(Request $request)
    {
        $oss = new AliyunOss();
        $request->session()->push('hhahha','asdgfdasg');
        Redis::set('ceshi',111);
        dd($oss);

    }
}
