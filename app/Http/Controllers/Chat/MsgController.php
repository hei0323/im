<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class MsgController extends Controller
{
    public function index(){
        Session::put('a','bbb');
        Session::save();
        print_r(Session::all());
    }
}
