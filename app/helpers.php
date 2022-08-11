<?php

/**
 * 生成 apiToken
 */
if(function_exists('getApiToken')) {
    function getApiToken($private = '')
    {
        $exptime    = date("mdY");
        $jms_code   = 'JrWy2020Gj';
        $getip      = $private;
        $jms_code   = env('jms_token', $jms_code);
        $md5_token  = md5($exptime . md5($jms_code) . $getip);
        return $md5_token;
    }

}
