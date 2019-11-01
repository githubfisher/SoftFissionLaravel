<?php
namespace App\Http\Repositories;

use Log;
use Illuminate\Support\Str;
use App\Http\Utilities\Constant;
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
        if (empty($key) || Redis::hIncrby($key, 'times', 1) > 3) {
            $key = (string) Str::uuid();
        }
        $code = $this->captcha->getPhrase();
        Redis::set($key, $code);
        Redis::expire($key, Constant::CACHE_TTL_MINUTE);
        Log::debug(__FUNCTION__ . ' key:' . $key . ' captcha:' . $code);

        return [$key, $this->captcha->get()];
    }

    public function check($code, $key)
    {
        $saved = Redis::get($key);
        if ($saved === $code) {
            return true;
        }

        return false;
    }
}
