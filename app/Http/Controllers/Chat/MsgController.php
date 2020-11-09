<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\IMMsgList;
use App\Services\ChatAuthService;
use App\Services\ChatMsgService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MsgController extends Controller
{

    /**
     * 发送消息
     * @param ChatMsgService $chatMsgService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function send(ChatMsgService $chatMsgService, ChatAuthService $chatAuthService, Request $request)
    {

        try {
            //判断发送人是否在线
            $senderId = Session::get('member_id');
            if (!$chatAuthService->isOnline($senderId)) {
                throw new \Exception("您已掉线，请重新登录！");
            }
            //获取会话信息
            $sessionInfo = $chatMsgService->getSessionInfo($request->session_uuid);
            if (!$sessionInfo) {
                throw new \Exception("会话不存在！");
            }
            //用户会话信息
            /*if($sessionInfo['session_type'] = 1 || $sessionInfo['session_type'] = 4){
                $userSessionInfo = $chatMsgService->getUserSessionInfo($request->session_uuid,$senderId);
                if(!$userSessionInfo){
                    throw new \Exception("获取用户会话信息错误！");
                }
            }*/

            //获取接收人/发送人信息
            $senderInfo = $chatAuthService->memberInfo($senderId);
            //持久化存储消息
            $msgId = $chatMsgService->saveMsg($senderInfo, $sessionInfo, $request->post());
            if (!$msgId) {
                throw new \Exception("消息存储失败！");
            }
            //更新会话信息
            $chatMsgService->updateSession($msgId, $senderInfo, $request->post());
            //更新接收人会话状态
            $isOnline = $chatAuthService->isOnline($request->receiverId);
            $chatMsgService->updateUserSession($isOnline, $msgId, $request->post());

            //发送数据
            $result = $chatMsgService->sendMsg($senderInfo, $sessionInfo, $request->post());
            if ($result === true) {
                return response()->json(['code' => 0, 'msg' => '发送完成！', 'data' => []]);
            } elseif ($result === 1000) {
                return response()->json(['code' => 1000, 'msg' => '用户不在线！', 'data' => []]);
            } else {
                return response()->json(['code' => 1010, 'msg' => 'failed！', 'data' => []]);
            }
        } catch (\Exception $exception) {
            $data['code'] = $exception->getCode();
            $data['message'] = $exception->getMessage();
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
            $data['previous'] = $exception->getPrevious();
            $data['trace'] = $exception->getTrace();
            return response()->json(['code' => 1010, 'msg' => $exception->getMessage(), 'data' => $data]);
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
        if ($result) {
            return response()->json(['code' => 0, 'msg' => 'ok！', 'data' => []]);
        } else {
            return response()->json(['code' => 1000, 'msg' => 'failed！', 'data' => []]);
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
        if ($result) {
            return response()->json(['code' => 0, 'msg' => 'ok！', 'data' => []]);
        } else {
            return response()->json(['code' => 1000, 'msg' => 'failed！', 'data' => []]);
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
        if ($result) {
            return response()->json(['code' => 0, 'msg' => 'ok！', 'data' => $result]);
        } else {
            return response()->json(['code' => 1000, 'msg' => 'failed!', 'data' => []]);
        }
    }


}
