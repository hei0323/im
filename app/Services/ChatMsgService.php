<?php


namespace App\Services;


use App\Models\IMGroup;
use App\Models\IMMsg;
use App\Models\IMMsgList;
use App\Models\IMMsgOffline;
use App\Models\Member;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use phpDocumentor\Reflection\Types\False_;

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
     * @param $senderId
     * @return bool|mixed
     */
    public function saveMsg($senderId)
    {
        //存入数据表msg
        $msgModel = new IMMsg();
        $msgModel->receiver_id = $this->request->receiver_id;
        $msgModel->contents = $this->request->contents;
        $msgModel->type = $this->request->type;
        $msgModel->sender_id = $senderId;
        if($msgModel->save()){
            $this->msgId =  $msgModel->msg_id;
            $this->msgListAdd($this->request->receiver_id,$senderId,$this->msgId,$this->request->type);
            $this->unreadMsgAdd($senderId);
            return $this->msgId;
        }else{
            return false;
        }
    }

    /**
     * 离线消息同步库存储
     */
    public function saveOffline($senderId){

        switch ($this->request->type){
            case 0:
            case 1:
                $this->offlinePerson($senderId);
                break;
            case 2:
                $this->offlineGroup($senderId);
                break;
            case 3:
                $this->offlineAll($senderId);
                break;
            default:
                return false;
        }
    }

    /**
     * 新增用户消息列表
     * @param $receiverId
     * @param $senderId
     * @param $msgId
     * @param $type
     * @return bool
     */
    public function msgListAdd($receiverId,$senderId,$msgId,$type){

        if($type==2){
            $groupMembers = Gateway::getAllGroupIdList($this->request->group_id);
            if(!empty($groupMembers)){
                foreach ($groupMembers as $value){
                    $result = Redis::hset('im_list:'.$value,$senderId,$msgId);
                }
                return true;
            }else{
                return false;
            }
        }else{
            $result = Redis::hset('im_list:'.$receiverId,$senderId,$msgId);
        }
        return $result;
    }

    /**
     * 获取用户消息列表
     * @param $receiverId
     * @return array
     */

    public function msgListShow($receiverId)
    {
        $listArr =  IMMsgList::with(['member:member_id,member_name','group:title,avatar,group_id'])->get()->toArray();
        $unreadArr = Redis::hgetall('im_unread:'.$receiverId);

        foreach ($listArr as $key=>$value){
            if (array_key_exists($value['sender_id'],$unreadArr)){
                $unread = unserialize($unreadArr[$value['sender_id']]);
                $listArr[$key]['unread_num'] = $unread['num'];
            }else{
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
    public function msgListSave($receiverId){
        $msgList = Redis::hgetall('im_list:'.$receiverId);
        $msgList = DB::table('im_msg')
            ->whereIn('msg_id',$msgList)
            ->orderBy('readed_at','asc')
            ->orderBy('msg_id','desc')
            ->get();
        foreach ($msgList as $key=>$value){
            IMMsgList::updateOrCreate(
                ['sender_id'=>$value->sender_id,'receiver_id'=>$value->receiver_id],
                ['msg_id'=>$value->msg_id,'receiver_id'=>$value->receiver_id,'sender_id'=>$value->sender_id,
                    'group_id'=>$value->group_id,'contents'=>$value->contents,'type'=>$value->type]
            );
        }
        return true;
    }

    /**
     * 删除未读消息数
     * @param $senderId
     * @return mixed
     */
    public function unreadMsgDel($receiverId){
        if($this->request->reset == 1){
            return Redis::hdel('im_unread:'.$receiverId);
        }else{
            return Redis::hdel('im_unread:'.$receiverId,$this->request->receiver_id);
        }
    }

    /**
     * 发送数据
     * @return bool|int
     * @throws \Exception
     */
    public function sendMsg($content){

        if($this->request->type==1){
            if($this->isOnline($this->request->receiver_id)){
                Gateway::sendToUid($this->request->receiver_id,$content);
            }else{
                return 1000;
            }
        }elseif($this->request->type == 2){
            Gateway::sendToGroup($this->request->receive_id,$content);
        }elseif($this->request->type == 3){
            Gateway::sendToAll($content);
        }
        return true;
    }

    /**
     * 存储全局离线消息
     * @param $senderId
     * @return bool|mixed
     */
    private function offlineAll(&$senderId){
        $groupMembers = Gateway::getAllUidList();
        if(!empty($groupMembers)){
            return $this->offlinePerson($senderId);
        }else{
            return false;
        }
    }

    /**
     * 存储群组离线消息
     * @param $senderId
     * @return bool|mixed
     */
    private function offlineGroup(&$senderId){
        $groupMembers = Gateway::getAllGroupIdList($this->request->group_id);
        if(!empty($groupMembers)){
            foreach ($groupMembers as $value){
                $this->offlinePerson($senderId);
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * 存储个人离线消息
     * @param $senderId
     * @return bool|mixed
     */
    private function offlinePerson(&$senderId){

        if($this->isOnline($senderId)) return true;

        $msgOfflineModel = new IMMsgOffline();
        $msgOfflineModel->msg_id = $this->msgId;
        $msgOfflineModel->contents = $this->request->contents;
        $msgOfflineModel->sender_id = $senderId;
        $msgOfflineModel->receiver_id = $this->request->receiver_id;
        $msgOfflineModel->type = $this->request->type;
        $msgOfflineModel->group_id = $this->request->type == 2?$this->request->receiver_id:0;

        if($msgOfflineModel->save()){
            return $msgOfflineModel->id;
        }else{
            return false;
        }

    }


    /**
     * 更新未读消息数
     * @param $senderId
     * @return mixed
     */
    private function unreadMsgAdd(&$senderId){
        $unread = [];
        if($unread = Redis::hget('im_unread:'.$this->request->receiver_id,$senderId)){
            $unread = unserialize($unread);
            $unread['num'] +=1;
        }else{
            $unread['sid'] =$senderId;
            $unread['mid'] =$this->msgId;
            $unread['num'] =1;
        }
        return Redis::hset('im_unread:'.$this->request->receiver_id,$senderId,serialize($unread));
    }


    /**
     * 同步拉取离线消息
     * @param $receiverId
     * @return bool
     */
    public function sendOfflineMsg($receiverId)
    {
        //判断是否在线
        if(!$this->isOnline($receiverId)) return false;
        //获取离线消息
        $offlineMsg = IMMsgOffline::with(['member:member_id,member_name','group:title,avatar,group_id'])->get()->toArray();
        Gateway::sendToUid($receiverId, json_encode(array(
            'type'      => 'offline_pull',
            'data' => $offlineMsg
        )),256);
    }

    /**
     * 判断是否在线
     * @param $userId
     * @return int
     */
    private function isOnline($userId){
        return Gateway::isUidOnline($userId);
    }

    /**
     * 组装返回数据
     * @param $senderId
     * @param $sendType
     * @return array|bool
     */
    public function makeUpData($senderId,$sendType)
    {
        switch ($sendType){
            case 'msg':return $this->makeOnlineMsg($senderId);break;//初始化连接
            default:return false;
        }
    }

    /**
     * 返回普通消息数据
     * @param $senderId
     * @return array
     */
    private function makeOnlineMsg(&$senderId){
        $senderData =  Member::find($senderId,['member_id','member_avatar','member_name']);
        $content = [
            'code'=>0,
            'type'=>'msg',
            'data'=>[
                'sender_id'=>$senderData->member_id,
                'sender_avatar'=>$senderData->member_avatar,
                'sender_name'=>$senderData->member_name,
                'sender_content'=>$this->request->contents,
            ]
        ];
        return json_encode($content,256);
    }

}