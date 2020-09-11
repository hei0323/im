<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisTestController extends Controller
{
    //
    public function index(Request $request)
    {
        $request->session()->push('hhahha','asdgfdasg');
        //dd($request->session()->all());
        Redis::set('ceshi',111);
    }
}
