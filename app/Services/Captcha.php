<?php
namespace App\Services;

use Log;
use Illuminate\Support\Str;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Redis;

class Captcha
{
    protected $captcha = null;

    public function __construct()
    {
        $this->captcha = (new CaptchaBuilder())->build();
    }

    public function get($key = '')
    {
        $config = config('captcha');
        if (empty($key) || Redis::hIncrby($key, 'times', 1) > $config['key_max_times']) {
            $key = (string) Str::uuid();
        }
        $code = $this->captcha->getPhrase();
        Redis::hSet($key, 'code', $code);
        Redis::expire($key, $config['cache_ttl']);
        Log::debug(__FUNCTION__ . ' key:' . $key . ' captcha:' . $code);

        return [$key, $this->captcha->get()];
    }

    public function check($code, $key)
    {
        $saved = Redis::hGet($key, 'code');
        Log::debug(__FUNCTION__ . ' key: ' . $key . ' code:' . $code . ' saved:' . $saved);
        if ($saved === $code) {
            return true;
        }

        return false;
    }
}
