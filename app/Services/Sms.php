<?php
namespace App\Services;

use Log;
use App\Utilities\Constant;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Redis;

class Sms
{
    protected $sms = null;

    public function __construct()
    {
        $this->sms = new EasySms(config('easy_sms'));
    }

    public function send($mobile, $params)
    {
        return [];

        return $this->sms->send($mobile, $params);
    }

    public function hasSent($mobile, $scene)
    {
        $key = sprintf(Constant::AUTH_SMS_SEND, $scene, $mobile);
        if (Redis::exists($key)) {
            Log::debug(__FUNCTION__ . ' key(' . $key . ') 已发送过短信验证码,且未过期!');

            return true;
        }

        return false;
    }

    public function sendCode($mobile, $scene, $length = 4)
    {
        try {
            $code   = randCode($length);
            $config = config('sms');
            $res    = $this->send($mobile, [
                'template' => $config['scene'][$scene]['template_id'],
                'data'     => [$code],
            ]);
            $key = sprintf(Constant::AUTH_SMS_SEND, $scene, $mobile);
            Redis::set($key, $code);
            Redis::expire($key, $config['scene'][$scene]['cache_ttl']);
            Log::debug(__FUNCTION__ . ' key(' . $key . ') code:' . $code . ' ttl:' . $config['scene'][$scene]['cache_ttl'] . ' res:' . json_encode($res));

            return true;
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            Log::error(__FUNCTION__ . ' ' . json_encode($e->results));
        }

        return false;
    }

    public function check($mobile, $code, $scene)
    {
        $key   = sprintf(Constant::AUTH_SMS_SEND, $scene, $mobile);
        $saved = Redis::get($key);
        Log::debug(__FUNCTION__ . ' key(' . $key . ') code:' . $code . ' saved:' . $saved);
        if ($saved === $code) {
            return true;
        }

        return false;
    }
}
