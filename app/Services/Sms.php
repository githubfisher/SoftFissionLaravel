<?php
namespace App\Services;

use Log;
use Overtrue\EasySms\EasySms;
use App\Http\Utilities\Constant;
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
        //return [];
        return $this->sms->send($mobile, $params);
    }

    public function hasSent($mobile, $scene)
    {
        if (Redis::exists(sprintf(Constant::AUTH_SMS_SEND, $scene, $mobile))) {
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
            Log::debug(__FUNCTION__ . ' key: ' . $key . ' code:' . $code . ' res:' . json_encode($res));
            Redis::set($key, $code);
            Redis::expire($key, $config['scene'][$scene]['cache_ttl']);

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
        Log::debug(__FUNCTION__ . ' key: ' . $key . ' code:' . $code . ' saved:' . $saved);
        if ($saved === $code) {
            return true;
        }

        return false;
    }
}
