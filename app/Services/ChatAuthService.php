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

class ChatAuthService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function bind(){
        
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
     * 获取设备标识
     */
    public function getDeviceType()
    {

    }
}