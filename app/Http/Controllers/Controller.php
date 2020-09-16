<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //返回JSON数据
    public function jsonReturn($code = 0, $data = [],$msg = '',$status='',$headers='',$options='') {
        $dataReturn['code'] = $code;
        $dataReturn['msg'] = empty($msg) ? $this->getErrorMsg($code) : $msg;
        $dataReturn['data'] = $data;

        return response()->json($dataReturn,$status,$headers,$options);
    }

    //定义错误列表信息
    public function getErrorMsg($code = 1) {
        $msgList = array(
            1 => 'success',
        );
        return isset($msgList[$code]) ? $msgList[$code] : '未定义错误!';
    }

}
