<?php
namespace App\Traits;
use App\Models\User\WeChatApp;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use Illuminate\Support\Facades\Redis;

trait HandleAppCache
{
    // 数据库标记公众号解绑
    public function delApp(string $appId, int $userId = 0)
    {
        $condition                                = ['app_id' => $appId];
        ! empty($userId) && $condition['user_id'] = $userId;

        return WeChatApp::where($condition)->delete();
    }

    // 添加到解绑集合
    public function addUnbindSet(string $appId)
    {
        Redis::zAdd(Constant::UNBINDED_APP_ZSET, time(), $appId);
    }

    // 不校验是否在集合, 直接执行清除操作
    public function remUnbindSet(string $appId)
    {
        Redis::zRem(Constant::UNBINDED_APP_ZSET, $appId);
    }

    // 仅返回绑定中的公众号
    public function first(string $appId)
    {
        return WeChatApp::where(['app_id' => $appId])->first();
    }

    // 创建或更新公众号信息
    public function updateOrCreate(string $appId, array $appInfo)
    {
        return WeChatApp::withTrashed()->updateOrCreate(['app_id' => $appId], $appInfo);
    }

    // 刷新用户名下公众号列表
    public function refreshAppList(int $userId, array $appInfo)
    {
        $list            = $this->list($userId);
        $list['current'] = $appInfo['app_id'];
        if ( ! in_array($appInfo['app_id'], array_keys($list['list']))) {
            $List['list'][$appInfo['app_id']] = $appInfo;
        }
        $key = sprintf(Constant::BIND_APP_LIST, $userId);
        Redis::set($key, json_encode($list));

        return $list;
    }

    // 刷新公众号缓存信息
    public function refreshAppInfo(array $appInfo)
    {
        $key = sprintf(Constant::BIND_APP_INFO, $appInfo['app_id']);
        Redis::set($key, json_encode($appInfo));
    }

    // 用户名下公众号列表
    public function list(int $userId)
    {
        $key  = sprintf(Constant::BIND_APP_LIST, $userId);
        $list = Redis::get($key);
        $list = json_decode($list, true);
        if ($list === false || ! isset($list['current']) || ! isset($list['list']) || ! is_array($list['list'])) {
            $list = [
                'current' => '',
                'list'    => [

                ],
            ];
            $appList = WeChatApp::where('user_id', $userId)->get();
            if ($appList && $appList->isNotEmpty()) {
                $appList         = $appList->toArray();
                $list['list']    = array_column($appList, null, 'app_id');
                $list['current'] = $appList[0]['app_id'];
            }

            Redis::set($key, json_encode($list));
        }

        return $list;
    }

    // 公众号信息
    public function getAppInfo(string $appId)
    {
        $key     = sprintf(Constant::BIND_APP_INFO, $appId);
        $appInfo = Redis::get($key);
        $appInfo = json_decode($appInfo, true);
        if ($appInfo === false) {
            $appInfo = $this->first($appId);
            if ($appInfo) {
                Redis::set($key, json_encode($appInfo));
            }
        }

        return $appInfo;
    }

    // 切换公众号
    public function switchApp(int $userId, string $appId)
    {
        $appInfo = $this->getAppInfo($appId);
        if ($appInfo) {
            $list   = $this->refreshAppList($userId, $appInfo);
            $appIds = array_column($list['list'], 'app_id');
            if ( ! in_array($appId, $appIds)) {
                return FeedBack::SWITCH_FAIL;
            }

            return true;
        }

        return FeedBack::WECHAT_APP_NOT_FOUND;
    }

    // 解绑公众号
    public function unbind(string $appId, int $userId = 0)
    {
        $res = $this->delApp($appId, $userId);
        if ($res) {
            $this->addUnbindSet($appId);
        }

        return true;
    }

    public function isBinded(string $appId)
    {
        return $appId;
    }
}
