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
        //if($chatAuthService->forbiddenRequest()) return response()->json(['code'=>10010,'msg'=>'禁止未知设备访问！'],401);
        //禁止游客访问
        if($touristMode){
            if($chatAuthService->isTourist()) return response()->json(['code'=>1011,'msg'=>'禁止游客访问！'],401);
        }

        //多域名-CORS跨域方案-服务端
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allowOrigin = array(
            'http://ai.mycjj.com:8091','http://web.newweb.com', 'http://web.chejj.cn', 'http://web.mycjj.com', 'https://web.chejj.cn', 'https://web.mycjj.com',
            'http://admin.newweb.com', 'http://admin.chejj.cn', 'http://admin.mycjj.com', 'https://admin.chejj.cn', 'https://admin.mycjj.com',
            'http://www.newweb.com', 'http://www.chejj.cn', 'http://www.mycjj.com', 'https://www.chejj.cn', 'https://www.mycjj.com',
            'http://4s.newweb.com', 'http://4s.chejj.cn', 'http://4s.mycjj.com', 'https://4s.chejj.cn', 'https://4s.mycjj.com',
            'http://api.newweb.com', 'http://api.chejj.cn', 'http://api.mycjj.com', 'https://api.chejj.cn', 'https://api.mycjj.com');
        if (in_array($origin, $allowOrigin)) {
            $response->header('Access-Control-Allow-Origin',$origin);
        }
        $response->header('Access-Control-Allow-Headers','Origin,X-Requested-With,Content-Type,Accept,Authorization,Cookie');
        $response->header('Access-Control-Allow-Methods','GET,POST,PUT,');
        $response->header('Access-Control-Allow-Credentials',true); //允许携带cookie
        $response->header('Access-Control-Max-Age',1728000); //减少预检请求次数

        return $response;
    }
}
