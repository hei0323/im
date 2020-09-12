<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class MsgController extends Controller
{
    /**
     * 发送消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request){
        $receiveId = $request->receive_id;
        $contents = $request->contents;
        $type = $request->type;
        if($type==1){
            if(Gateway::isUidOnline($receiveId)){
                Gateway::sendToUid($receiveId,$contents);
            }else{
                return response()->json(['code'=>1000,'msg'=>'用户不在线！','data'=>[]]);
            }
        }elseif($type==2){
            Gateway::sendToGroup($receiveId,$contents);
        }elseif($type==3){
            Gateway::sendToAll($contents);
        }
        return response()->json(['code'=>0,'msg'=>'发送完成！','data'=>[]]);
    }
}
