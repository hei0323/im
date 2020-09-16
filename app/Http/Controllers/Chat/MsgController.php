<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\IMMsgList;
use App\Services\ChatMsgService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MsgController extends Controller
{

    /**
     * 发送消息
     * @param ChatMsgService $chatMsgService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function send(ChatMsgService $chatMsgService){

        //持久化存储消息
        $senderId = Session::get('member_id');
        $msgId = $chatMsgService->saveMsg($senderId);
        if(!$msgId) {
            return response()->json(['code'=>1011,'msg'=>'消息发送失败！','data'=>[]]);
        }

        //离线消息存储
        $chatMsgService->saveOffline($senderId);

        //发送数据
        $counts = $chatMsgService->makeUpData($senderId,'msg');
        $result = $chatMsgService->sendMsg($counts);
        if($result == true){
            return response()->json(['code'=>0,'msg'=>'发送完成！','data'=>[]]);
        }elseif($result == 1000){
            response()->json(['code'=>1000,'msg'=>'用户不在线！','data'=>[]]);
        }else{
            response()->json(['code'=>1010,'msg'=>'failed！','data'=>[]]);
        }

    }


    /**
     * 清空未读消息数
     * @param ChatMsgService $chatMsgService
     * @return \Illuminate\Http\JsonResponse
     */
    public function receipt(ChatMsgService $chatMsgService)
    {
        $receiverId = Session::get('member_id');
        $result = $chatMsgService->unreadMsgDel($receiverId);
        if($result) {
            return response()->json(['code'=>0,'msg'=>'ok！','data'=>[]]);
        }else{
            return response()->json(['code'=>1000,'msg'=>'failed！','data'=>[]]);
        }
    }


    /**
     * 持久化消息列表
     * @param ChatMsgService $chatMsgService
     * @return \Illuminate\Http\JsonResponse
     */
    public function msgListSave(ChatMsgService $chatMsgService)
    {
        $receiverId = Session::get('member_id');
        $result = $chatMsgService->msgListSave($receiverId);
        if($result) {
            return response()->json(['code'=>0,'msg'=>'ok！','data'=>[]]);
        }else{
            return response()->json(['code'=>1000,'msg'=>'failed！','data'=>[]]);
        }
    }


    /**
     * 消息列表
     * @param ChatMsgService $chatMsgService
     * @return \Illuminate\Http\JsonResponse
     */
    public function msgListShow(ChatMsgService $chatMsgService)
    {
        $receiverId = Session::get('member_id');
        $result = $chatMsgService->msgListShow($receiverId);
        if($result) {
            return response()->json(['code'=>0,'msg'=>'ok！','data'=>$result]);
        }else{
            return response()->json(['code'=>1000,'msg'=>'failed！','data'=>[]]);
        }
    }


}
