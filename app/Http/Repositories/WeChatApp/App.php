<?php
namespace App\Http\Repositories\WeChatApp;

use App\Models\User\WeChatApp;
use App\Http\Utilities\Constant;
use Illuminate\Support\Facades\Redis;

class App
{
    public function addUnbindSet($appId)
    {
        Redis::zAdd(Constant::UNBINDED_APP_ZSET, time(), $appId);
        WeChatApp::where('app_id', $appId)->delete();
    }

    public function remUnbindSet($appId)
    {
        $rank = Redis::zRank(Constant::UNBINDED_APP_ZSET, $appId);
        if (is_numeric($rank)) {
            Redis::zRem(Constant::UNBINDED_APP_ZSET, $appId);
        }
    }

    public function first($condition)
    {
        return WeChatApp::where($condition)->first();
    }

    public function updateOrCreate($appId, $appData)
    {
        return WeChatApp::withTrashed()->updateOrCreate(['app_id' => $appId], $appData);
    }

    public function refreshAppList($userId, $currentAppId)
    {
        $list = WeChatApp::where('user_id', $userId)->get();
        $list = $list ? $list->toArray() : [];
        $list = [
            'currentId' => $currentAppId,
            'list'      => $list,
        ];
        $key = sprintf(Constant::BIND_APP_LIST, $userId);
        Redis::set($key, json_encode($list));
    }

    public function refreshAppInfo($appId)
    {
        //更新单个APP信息
        $key     = sprintf(Constant::BIND_APP_INFO, $appId);
        $appInfo = WeChatApp::withTrashed()->where('app_id', $appId)->first();
        if ($appInfo) {
            $appInfo = $appInfo->toArray();
            Redis::set($key, json_encode($appInfo));
        }
    }
}
