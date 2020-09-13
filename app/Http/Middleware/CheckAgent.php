<?php

namespace App\Http\Middleware;

use App\Services\ChatAuthService;
use Closure;
use Illuminate\Http\Request;

class CheckAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$touristMode = 0)
    {
        $chatAuthService = new ChatAuthService($request);
        //禁止未知设备访问
        if($chatAuthService->forbiddenRequest()) return response()->json(['code'=>10010,'msg'=>'禁止未知设备访问！'],401);
        //禁止游客访问
        if($touristMode){
            if($chatAuthService->isTourist()) return response()->json(['code'=>1011,'msg'=>'禁止游客访问！'],401);
        }

        return $next($request);

    }
}
