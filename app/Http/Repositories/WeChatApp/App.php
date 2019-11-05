<?php
namespace App\Http\Repositories\WeChatApp;

use App\Models\User\WeChatApp;
use App\Http\Utilities\Constant;
use App\Http\Utilities\FeedBack;
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

        return $list;
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

    public function list($userId)
    {
        return WeChatApp::where('user_id', $userId)->get();
    }

    public function switchApp($userId, $appId)
    {
        $list   = $this->refreshAppList($userId, $appId);
        $appIds = array_column($list['list'], 'app_id');
        if ( ! in_array($appId, $appIds)) {
            return FeedBack::SWITCH_FAIL;
        }

        return true;
    }

    public function unbind($userId, $appId)
    {
        WeChatApp::where(['user_id' => $userId, 'app_id' => $appId])->delete();
        $this->addUnbindSet($appId);

        return true;
    }
}
