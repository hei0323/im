<?php


namespace App\Services;


use App\Models\Member;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Facades\Agent;

class ChatAuthService
{
    private $request;
    private $memberId;
    public $platform = ['Windows','IOS','AndroidOS','Linux','Ubuntu'];
    public $device = ['WebKit','Samsung','Nexus','iPhone','HTC','iPad'];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->memberId = Session::get('member_id');
    }


    public function bind(){

        //判断是否有绑定
        if(Gateway::getUidByClientId($this->request->client_id)){return 1001;}

        //获取请求客户端类型
        $clientType = $this->getClientType(Agent::platform());

        //进行绑定并加入客户端类型组
        Gateway::bindUid($this->request->client_id,$this->memberId);
        Gateway::joinGroup($this->request->client_id,'client_type_'.$clientType);

        //踢掉原client_id
        $clientIds = Gateway::getClientIdByUid($this->memberId);
        if(!empty($clientIds)){
            //获取$clientType设备在线client_id
            $groupClientList = Gateway::getAllGroupClientIdList('client_type_'.$clientType);
            if(!empty($groupClientList)){
                foreach ($clientIds as $value){
                    if(in_array($value,$groupClientList)){
                        Gateway::closeClient($value);
                    }
                }
            }
        }

        return true;
    }

    public function memberInfo($memberId = null){
        if($memberId == null){
            $memberId = $this->memberId;
        }
        $memberInfo = Member::find($memberId,['member_id','member_avatar','member_name']);
        if(!empty($memberInfo)){
            return $memberInfo;
        }else{
            return [];
        }
    }


    /**
     * 判断用户是否在线
     * @param $userId
     * @return int
     */
    public function isOnline($userId){
        return Gateway::isUidOnline($userId);
    }
    

    /**
     * 设置客户端通讯标识ID
     * @param string $type
     * @return int
     */
    private function getClientType(string $type)
    {
        switch ($type){
            case 'Windows':return 1;break;
            case 'AndroidOS':return 2;break;
            case 'IOS':return 3;break;
            case 'Linux':return 4;break;
            case 'Ubuntu':return 5;break;
            default:return 0;
        }
    }


    /**
     * 禁止访问过滤
     * @return bool
     */
    public function forbiddenRequest(){
        if (Agent::isRobot()) return true;
        if(!in_array(Agent::platform(),$this->platform)){
            return true;
        }

        return false;
    }


    /**
     * 是否是游客
     * @return bool
     */
    public function isTourist(){
        if(is_numeric($this->memberId)){
            return false;
        }else{
            return true;
        }
    }
}