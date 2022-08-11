<?php

namespace App\Http\Controllers\Api;

class OauthController extends ApiBaseController
{
    public function apiToken(){
        $exptime    = date("mdY");
        $jms_code   = 'JrWy2020Gj';
        $getip      = request()->ip();
        $jms_code   = env('jms_token', 'JrWy2020Gj'); //.env 定义 VPN_TOKEN
        $md5_token  = md5($exptime . md5($jms_code) . $getip);
        return $this->success(['apiToken' => $md5_token]);
    }
}
