<?php
namespace App\Http\Traits;

use App\Http\Utilities\WechatCache as Cache;

trait WechatCache
{
    /**
     * 设置EasyWechatSDK的缓存实例
     * @author fisher
     * @date 2019-03-29 09:31
     */
    public function setWechatCache()
    {
        if (property_exists($this, 'openPlatform')) {
            $this->openPlatform->rebind('cache', new Cache());
        }
    }
}
