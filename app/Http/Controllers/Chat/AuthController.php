<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * 用户客户端绑定
     * @param $client_id
     */
    public function bind(Request$request,$client_id)
    {
        $result = $request->header();
        dd($result);

        die;
        //唯一设备绑定用户唯一id
        $memberId = Session::get('member_id');
        $clientIds = Gateway::getClientIdByUid($memberId);
        if(!empty($clientIds)){
            foreach ($clientIds as $value){
                Gateway::closeClient($value);
            }
        }
        Gateway::bindUid($client_id,$memberId);

        return response()->json(['code'=>0,'msg'=>'用户绑定完成！']);
    }
}
