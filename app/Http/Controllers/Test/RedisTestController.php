<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Repositorys\Contracts\OssInterface;
use App\Validate\TestRedisValidate;
use Illuminate\Http\Request;

class RedisTestController extends Controller
{
    //
    public function index(Request $request,OssInterface $oss,TestRedisValidate $testRedisValidate)
    {

        //数据验证
        if (!$testRedisValidate->scene('add')->check($request->all())) {
            var_dump($testRedisValidate->getError());
        }


        $result = $oss->put('images/ceshi.txt','hello word!');
        echo '<pre>';
        print_r($result);die;
    }
}
