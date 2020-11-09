<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\IMNoticeFriend;
use App\Services\ChatAuthService;
use App\Services\ChatMsgService;
use App\Services\RelationService;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    //好友申请
    public function addApply(Request $request,ChatAuthService $chatAuthService)
    {
        try {
            $receiverInfo = $chatAuthService->memberInfo($request->receiverId);
            if (!$receiverInfo) {
                throw new \Exception("用户不存在！");
            }
            $memberId = \Session::get('member_id');
            $applyerInfo = $chatAuthService->memberInfo($memberId);
            if (!$applyerInfo) {
                throw new \Exception("用户不存在！");
            }
            $noticeModel = new IMNoticeFriend();
            $noticeModel->receiver_id = $request->receiverId;
            $noticeModel->receiver_name = $receiverInfo['member_name'];
            $noticeModel->type = 1;
            $noticeModel->source = $request->source;
            $noticeModel->message = $request->message;
            $noticeModel->applyer_id = $memberId;
            $noticeModel->applyer_name = $applyerInfo['member_name'];
            $result = $noticeModel->save();
            if($result){
                return response()->json(['code'=>0,'msg'=>'ok','data'=>[]]);
            }else{
                throw new \Exception("请求失败！");
            }
        }catch (\Exception $exception){
            $data['code'] = $exception->getCode();
            $data['message'] = $exception->getMessage();
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
            $data['previous'] = $exception->getPrevious();
            $data['trace'] = $exception->getTrace();
            return response()->json(['code' => 1010, 'msg' => $exception->getMessage(), 'data' => $data]);
        }
    }

    //申请确认
    public function addConfirm(Request $request,RelationService $relationService,ChatMsgService $chatMsgService)
    {
        try {
            $memberId = \Session::get('member_id');
            $noticeModel = IMNoticeFriend::where('receiver_id',$memberId)->find($request->get('noticeId'));
            if(empty($noticeModel)) {
                throw new \Exception('通知不存在！');
            }
            if($noticeModel->state == 1){
                throw new \Exception('已同意添加好友！');
            }
            if($noticeModel->state == 2){
                throw new \Exception('已拒绝添加好友！');
            }
            //添加好友
            if($request->get('isAgree') == 1){
                $relationService->addFriends();
            }else{
                $relationService->refuseFriends();
            }
            //发送初始消息
            $chatMsgService->sendMsg('','','');

        }catch (\Exception $exception){
            $data['code'] = $exception->getCode();
            $data['message'] = $exception->getMessage();
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
            $data['previous'] = $exception->getPrevious();
            $data['trace'] = $exception->getTrace();
            return response()->json(['code' => 1010, 'msg' => $exception->getMessage(), 'data' => $data]);
        }
    }

    //删除好友
    public function del()
    {

    }

    //好友列表
    public function list()
    {

    }

    //申请通知列表
    public function noticeList(){

    }
}
