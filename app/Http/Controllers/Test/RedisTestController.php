<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Repositorys\Contracts\OssInterface;
use App\Validate\FilterWords;
use App\Validate\TestRedisValidate;
use Illuminate\Http\Request;

class RedisTestController extends Controller
{
    //
    public function index(Request $request,OssInterface $oss,TestRedisValidate $testRedisValidate)
    {
        //
        $filter = new FilterWords();
        $result = $filter->filter('u是个傻瓜dd蛋子haha哈哈中的国订单',1,5);

        var_dump($result);die;

        //数据验证
        if (!$testRedisValidate->scene('add')->check($request->all())) {
            var_dump($testRedisValidate->getError());
        }

        $result = $oss->put('images/ceshi.txt','hello word!');
        echo '<pre>';
        print_r($result);die;

    }
}
