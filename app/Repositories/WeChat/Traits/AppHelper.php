<?php
namespace App\Repositories\WeChat\Traits;

use App\Utilities\Constant;
use Prettus\Repository\Traits\CacheableRepository;

trait AppHelper
{
    use CacheableRepository;

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
