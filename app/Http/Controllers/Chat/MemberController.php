<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Member;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * 获取在线用户列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function online()
    {
        $memberIds = Gateway::getAllUidList();
        if(!empty($memberIds)){
            $columns = [
                'member_id','member_name','member_truename','member_avatar','member_sex',
                'member_birthday','member_login_ip','member_login_time','store_id',
            ];
            $list = Member::whereIn('member_id',$memberIds)->get($columns);
            return response()->json(['code'=>0,'msg'=>'ok','data'=>['total'=>count($list),'list'=>$list]]);
        }else{
            return response()->json(['code'=>0,'msg'=>'暂无在线用户！','data'=>[]]);
        }
    }

    /**
     * 获取用户个人信息
     * @param $memberId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($memberId){
        $columns = [
            'member_id','member_name','member_truename','member_avatar','member_sex',
            'member_birthday','member_login_ip','member_login_time','store_id',
        ];
        $member = Member::find($memberId,$columns);
        if(!empty($member)){
            return response()->json(['code'=>0,'msg'=>'ok','data'=>$member]);
        }else{
            return response()->json(['code'=>1010,'msg'=>'用户不存在！','data'=>[]]);
        }

    }
}
