<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 请求成功返回信息
     *
     * @param array $data 返回数据
     * @param string $msg 成功信息
     * @return  mixed
     */
    protected function success($data = [], $msg = '')
    {
        return response()->json(['data' => $data, 'message' => empty($msg) ? 'success' : $msg, 'code' => 1]);
    }

    /**
     * 请求失败时返回信息
     *
     * @param string $message 错误信息
     * @return mixed
     */
    protected function error($message)
    {
        return response()->json(['message' => $message, 'code' => 0]);
    }
}
