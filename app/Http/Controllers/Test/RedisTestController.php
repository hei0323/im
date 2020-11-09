<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Repositorys\Contracts\OssInterface;
use Illuminate\Http\Request;

class RedisTestController extends Controller
{
    //
    public function index(Request $request,OssInterface $oss)
    {
        $result = $oss->put('images/ceshi.txt','hello word!');
        echo '<pre>';
        print_r($result);die;
    }
}
