<?php
namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * 返回成功的响应
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function suc($data = [], $status = 200, $headers = [])
    {
        return response()->json(array_merge(['code' => 0, 'message' => 'success'], $data), $status, $headers);
    }

    /**
     * 返回错误的响应
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function err($data = [], $status = 400, $headers = [])
    {
        return response()->json(array_merge(['code' => 0, 'message' => 'error'], $data), $status, $headers);
    }
}
