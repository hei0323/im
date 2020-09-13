<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\ChatAuthService;

class AuthController extends Controller
{

    /**
     * 用户客户端绑定
     * @param ChatAuthService $chatAuthService
     * @return \Illuminate\Http\JsonResponse
     */
    public function bind(ChatAuthService $chatAuthService)
    {
        if($code = $chatAuthService->bind()){
            return response()->json(['code'=>0,'msg'=>'用户绑定完成！']);
        }else{
            return response()->json(['code'=>$code,'msg'=>'用户绑定完成！']);
        }
    }
}
