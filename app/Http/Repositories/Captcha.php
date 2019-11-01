<?php
namespace App\Http\Repositories;

use Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Http\Services\Captcha as Capa;

class Captcha
{
    protected $captcha = null;

    public function __construct(Capa $captcha)
    {
        $this->captcha = $captcha;
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
