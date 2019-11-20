<?php
namespace App\Repositories\WeChat\Traits;

use App\Utilities\Constant;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

trait AppHelper
{
    /**
     * @var CacheRepository
     */
    protected $cacheRepository = null;

    /**
     * Set Cache Repository
     *
     * @param CacheRepository $repository
     *
     * @return $this
     */
    public function setCacheRepository(CacheRepository $repository)
    {
        $this->cacheRepository = $repository;

        return $this;
    }

    /**
     * Return instance of Cache Repository
     *
     * @return CacheRepository
     */
    public function getCacheRepository()
    {
        if (is_null($this->cacheRepository)) {
            $this->cacheRepository = app(config('repository.cache.repository', 'cache'));
        }

        return $this->cacheRepository;
    }

    // 用户名下公众号列表
    public function list(int $userId, $columns = ['*'])
    {
        $key   = sprintf(Constant::BIND_APP_LIST, $userId);
        $value = $this->getCacheRepository()->rememberForever($key, function () use ($userId, $columns) {
            $list = [
                'current' => null,
                'list'    => [],
            ];

            $appList = parent::where('user_id', $userId)->get();
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
}
