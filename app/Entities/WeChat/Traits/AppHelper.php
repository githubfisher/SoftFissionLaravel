<?php
namespace App\Entities\WeChat\Traits;

use App\Utilities\Constant;
use Illuminate\Support\Facades\Cache;

trait AppHelper
{
    // 用户名下公众号列表
    public function list(int $userId)
    {
        return Cache::rememberForever(sprintf(Constant::BIND_APP_LIST, $userId), function () use ($userId) {
            $list = [
                'current' => null,
                'list'    => [],
            ];

            $appList = static::where('user_id', $userId)->get();
            if ($appList->isNotEmpty()) {
                $appList         = $appList->toArray();
                $list['list']    = array_column($appList, null, 'app_id');
                $list['current'] = $appList[0]['app_id'];
            }

            return $list;
        });
    }

}
