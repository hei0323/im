<?php


namespace App\Services;


use App\Models\IMMsg;
use App\Models\IMMsgList;
use App\Models\IMMsgOffline;
use App\Models\IMSession;
use App\Models\IMUserSession;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ChatMsgService
{
    private $request;
    private $msgId;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 消息持久存储
     *
     * @param $senderInfo
     * @param $receiverInfo
     * @param $msgData
     * @return bool
     */
    public function saveMsg(&$senderInfo, &$sessionInfo, &$msgData)
    {
        if ($sessionInfo['session_type'] == 1) {
            $receiverId = $sessionInfo['receiver_id'];
        } else {
            $receiverId = $sessionInfo['group_id'];
        }

        $msgModel = new IMMsg();
        $msgModel->receiver_id = $receiverId;
        $msgModel->sender_id = $senderInfo['member_id'];
        $msgModel->sender_name = $senderInfo['member_name'];
        $msgModel->sender_avatar = $senderInfo['member_avatar'];
        $msgModel->contents = $msgData['contents'];
        $msgModel->session_id = $sessionInfo['session_id'];
        $msgModel->session_type = $sessionInfo['session_type'];
        if ($msgModel->save()) {
            return $this->msgId;
        } else {
            return false;
        }
    }

    /**
     * 更新会话信息
     * @param $msgId
     * @param $senderInfo
     * @param $msgData
     * @return bool
     */
    public function updateSession($msgId, &$senderInfo, &$msgData)
    {
        $sessionModel = IMSession::where('session_uuid', $msgData['session_uuid'])->find();
        $sessionModel->last_msg_id = $msgId;
        $sessionModel->last_msg_contents = $msgData['contents'];
        $sessionModel->last_msg_sender_id = $senderInfo['member_id'];
        $sessionModel->last_msg_sender_name = $senderInfo['member_name'];
        $sessionModel->last_msg_send_time = time();
        if ($sessionModel->save()) {
            return $this->msgId;
        } else {
            return false;
        }
    }


    public function updateUserSession($isOnline, $msgId, &$msgData)
    {
        $sessionUserModel = IMUserSession::where([['session_uuid', '=', $msgData['session_uuid']], ['user_id', '=', $msgData['receiverId']]])->find();
        if ($isOnline) {
            $sessionUserModel->msg_receive_id = $msgId;
            $sessionUserModel->mag_receive_time = time();
        } else {
            $sessionUserModel->msg_offline_id = $msgId;
            $sessionUserModel->mag_offline_time = time();
        }
        $sessionUserModel->unread_num += 1;
        if ($sessionUserModel->save()) {
            return $this->msgId;
        } else {
            return false;
        }
    }


    /**
     * 获取会话信息
     */
    public function getSessionInfo($uuid)
    {
        $userSessionModel = new IMSession();
        return $userSessionModel::with(['session_uuid', '=', $uuid])->get()->toArray();
    }

    /**
     * 获取用户会话信息
     */
    public function getUserSessionInfo($uuid, $memberId)
    {
        $userSessionModel = new IMUserSession();
        return $userSessionModel::with(['session_uuid', '=', $uuid], ['user_id', '=', $memberId])->get();
    }


    /**
     * 获取用户消息列表
     * @param $receiverId
     * @return array
     */

    public function msgListShow($receiverId)
    {
        $listArr = IMMsgList::with(['member:member_id,member_name', 'group:title,avatar,group_id'])->get()->toArray();

        $unreadArr = Redis::hgetall('im_unread:' . $receiverId);
        foreach ($listArr as $key => $value) {
            if (array_key_exists($value['sender_id'], $unreadArr)) {
                $unread = unserialize($unreadArr[$value['sender_id']]);
                $listArr[$key]['unread_num'] = $unread['num'];
            } else {
                $listArr[$key]['unread_num'] = 0;
            }
        }
        return $listArr;
    }

    /**
     * 持久化存储用户消息列表(用户离线时触发)
     * @param $receiverId
     * @return bool
     */
    public function msgListSave($receiverId)
    {
        $msgList = Redis::hgetall('im_list:' . $receiverId);
        $msgList = DB::table('im_msg')
            ->whereIn('msg_id', $msgList)
            ->orderBy('readed_at', 'asc')
            ->orderBy('msg_id', 'desc')
            ->get();
        foreach ($msgList as $key => $value) {
            IMMsgList::updateOrCreate(
                ['sender_id' => $value->sender_id, 'receiver_id' => $value->receiver_id],
                ['msg_id' => $value->msg_id, 'receiver_id' => $value->receiver_id, 'sender_id' => $value->sender_id,
                    'group_id' => $value->group_id, 'contents' => $value->contents, 'type' => $value->type]
            );
        }
        return true;
    }

    /**
     * 删除未读消息数
     * @param $senderId
     * @return mixed
     */
    public function unreadMsgDel($receiverId)
    {
        if ($this->request->reset == 1) {
            return Redis::hdel('im_unread:' . $receiverId);
        } else {
            return Redis::hdel('im_unread:' . $receiverId, $this->request->receiverId);
        }
    }

    /**
     * 发送数据
     * @return bool|int
     * @throws \Exception
     */
    public function sendMsg(&$senderInfo, &$sessionInfo, &$sendData)
    {

        $contents = $this->makeUpData(array_merge($senderInfo, $sendData), 'msg');
        switch ($sessionInfo['session_type']) {
            case 1:
                Gateway::sendToUid($sessionInfo['receiver_id'], $contents);
                break;
            case 2:
            case 3:
            case 4:
                Gateway::sendToGroup($sessionInfo['group_id'], $contents);
                break;
            case 0:
                Gateway::sendToAll($contents);
                break;
            default:
                return false;
        }
        return true;
    }

    /**
     * 存储全局离线消息
     * @param $senderId
     * @return bool|mixed
     */
    private function offlineAll(&$senderId)
    {
        $groupMembers = Gateway::getAllUidList();
        if (!empty($groupMembers)) {
            return $this->offlinePerson($senderId);
        } else {
            return false;
        }
    }

    /**
     * 存储群组离线消息
     * @param $senderId
     * @return bool|mixed
     */
    private function offlineGroup(&$senderId)
    {
        $groupMembers = Gateway::getAllGroupIdList($this->request->groupId);
        if (!empty($groupMembers)) {
            foreach ($groupMembers as $value) {
                $this->offlinePerson($senderId);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 存储个人离线消息
     * @param $senderId
     * @return bool|mixed
     */
    private function offlinePerson(&$senderId)
    {

        if ($this->isOnline($senderId)) return true;

        $msgOfflineModel = new IMMsgOffline();
        $msgOfflineModel->msg_id = $this->msgId;
        $msgOfflineModel->contents = $this->request->contents;
        $msgOfflineModel->sender_id = $senderId;
        $msgOfflineModel->receiver_id = $this->request->receiverId;
        $msgOfflineModel->type = $this->request->type;
        $msgOfflineModel->group_id = $this->request->type == 2 ? $this->request->receiverId : 0;

        if ($msgOfflineModel->save()) {
            return $msgOfflineModel->id;
        } else {
            return false;
        }

    }


    /**
     * 更新未读消息数
     * @param $senderId
     * @return mixed
     */
    private function unreadMsgAdd(&$senderId)
    {
        $unread = [];
        if ($unread = Redis::hget('im_unread:' . $this->request->receiverId, $senderId)) {
            $unread = unserialize($unread);
            $unread['num'] += 1;
        } else {
            $unread['sid'] = $senderId;
            $unread['mid'] = $this->msgId;
            $unread['num'] = 1;
        }
        return Redis::hset('im_unread:' . $this->request->receiverId, $senderId, serialize($unread));
    }


    /**
     * 同步拉取离线消息
     * @param $receiverId
     * @return bool
     */
    public function sendOfflineMsg($receiverId)
    {
        //判断是否在线
        if (!$this->isOnline($receiverId)) return false;
        //获取离线消息
        $offlineMsg = IMMsgOffline::with(['member:member_id,member_name', 'group:title,avatar,group_id'])->get()->toArray();
        Gateway::sendToUid($receiverId, json_encode(array(
            'type' => 'offline_pull',
            'data' => $offlineMsg
        )), 256);
    }

    /**
     * 判断是否在线
     * @param $userId
     * @return int
     */
    private function isOnline($userId)
    {
        return Gateway::isUidOnline($userId);
    }

    /**
     * 组装返回数据
     * @param $senderId
     * @param $sendType
     * @return array|bool
     */
    private function makeUpData($sendData, $sendType)
    {
        $contents = [
            'code' => 0,
            'type' => $sendType,
            'data' => [
                'sender_id' => $sendData['member_id'],
                'sender_avatar' => $sendData['member_avatar'],
                'sender_name' => $sendData['member_name'],
                'sender_content' => $sendData['contents'],
            ]
        ];

        return json_encode($contents, 256);
    }

    /**
     * 返回普通消息数据
     * @param $senderId
     * @return array
     */
    private function makeOnlineMsg(&$sendData)
    {
        $content = [
            'code' => 0,
            'type' => 'msg',
            'data' => [
                'sender_id' => $sendData['member_id'],
                'sender_avatar' => $sendData['member_avatar'],
                'sender_name' => $sendData['member_name'],
                'sender_content' => $sendData['contents'],
            ]
        ];
        return json_encode($content, 256);
    }

}
