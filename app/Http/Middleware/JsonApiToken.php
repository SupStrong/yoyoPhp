<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->header('apiToken');

        if($apiToken){
            $exptime    = date("mdY");
            $getip      = $request->header("privateKey");
            if (empty($getip)) {
                $getip      = $request->ip();
            }
            // $jms_code   = env('jms_token', 'JrWy2020Gj'); //.env 定义 VPN_TOKEN
            $jms_code   = config('app.jms_token'); //.env 定义 VPN_TOKEN
            $md5_token  = md5($exptime . md5($jms_code) . $getip);
            if ($apiToken  != $md5_token) {
                return response()->json([
                    'code' => 403,
                    'msg' => '校验错误，请重新请求'
                ]);
            }
            return $next($request);
        }
        return response()->json([
            'code' => 0,
            'msg' => '没有权限'
        ]);
    }
}
