<?php
namespace App\Repositories\WeChat\Traits;

use App\Utilities\Constant;
use Illuminate\Support\Facades\Redis;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Repository\Events\RepositoryEntityUpdated;

trait AppHelper
{
    use CacheableRepository;

    public function forceForget(string $key)
    {
        $this->getCacheRepository()->forget($key);
    }

    /**
     * 用户名下公众号列表
     *
     * @param int   $userId
     * @param array $columns
     *
     * @return mixed
     */
    public function list(int $userId, $columns = ['*'])
    {
        $key   = sprintf(Constant::BIND_APP_LIST, $userId);
        $value = $this->getCacheRepository()->rememberForever($key, function () use ($userId, $columns) {
            $list = [
                'current' => null,
                'list'    => [],
            ];

            $appList = parent::where('user_id', $userId)->get($columns);
            if ($appList->isNotEmpty()) {
                $appList         = $appList->toArray();
                $list['list']    = array_column($appList, null, 'app_id');
                $list['current'] = $appList[0]['app_id'];
            }

            return $list;
        });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * 刷新用户名下公众号列表
     *
     * @param int $userId
     *
     * @return mixed
     */
    public function refreshAppList(int $userId)
    {
        $this->forceForget(sprintf(Constant::BIND_APP_LIST, $userId));

        return $this->list($userId);
    }

    // 公众号信息
    public function getAppInfo(string $appId, $columns = ['*'])
    {
        $key     = sprintf(Constant::BIND_APP_INFO, $appId);
        $value   = $this->getCacheRepository()->rememberForever($key, function () use ($appId, $columns) {
            return parent::where('app_id', $appId)->first($columns);
        });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    // 刷新公众号缓存信息
    public function refreshAppInfo(string $appId)
    {
        $this->forceForget(sprintf(Constant::BIND_APP_INFO, $appId));

        return $this->getAppInfo($appId);
    }

    // 切换公众号
    public function switchApp(int $userId, string $appId)
    {
        $list = $this->list($userId);

        if (isset($list['current']) && $list['current'] == $appId) {
            return true;
        }
        
        $list['current'] = $appId;

        return $this->getCacheRepository()->put(sprintf(Constant::BIND_APP_LIST, $userId), $list);
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

    // 数据库标记公众号解绑
    public function delApp(string $appId, int $userId = 0)
    {
        $condition                                = ['app_id' => $appId];
        ! empty($userId) && $condition['user_id'] = $userId;

        return parent::deleteWhere($condition);
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

    public function updateOrCreate(array $attributes, array $values = [])
    {
        if ( ! is_null($this->validator)) {
            $this->validator->with(array_merge($attributes, $values))->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $modelName = trim($this->model(), '::class');
        $modelName = '\\' . $modelName;
        $model     = $modelName::withTrashed()->updateOrCreate($attributes, $values);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }
}
