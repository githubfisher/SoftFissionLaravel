<?php
namespace App\Http\Repositories;

use Log;
use Overtrue\EasySms\EasySms;
use App\Http\Utilities\Constant;
use Illuminate\Support\Facades\Redis;

class Sms
{
    protected $sms = null;

    public function __construct()
    {
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
                // 默认可用的发送网关
                'gateways' => [
                    'qcloud',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'qcloud' => [
                    'sdk_app_id'  => '1400048706',
                    'app_key'     => 'cfa16bee4b941d8e1bfbaac98eeaaa49',
                    'sign_name'   => '小黄人科技',
                ],
            ],
        ];

        $this->sms = new EasySms($config);
    }

    public function send($mobile, $params)
    {
        return $this->sms->send($mobile, $params);
    }

    public function hasSent($mobile)
    {
        if (Redis::exists(sprintf(Constant::AUTH_SMS_CODE, $mobile))) {
            return true;
        }

        return false;
    }

    public function sendCode($mobile, $length = 4)
    {
        try {
            $code = randCode($length);
            $res  = $this->send($mobile, [
                'template' => 224794,
                'data'     => [$code],
            ]);
            $key  = sprintf(Constant::AUTH_SMS_CODE, $mobile);
            Log::debug(__FUNCTION__ . ' key: ' . $key . ' code:' . $code . ' res:' . json_encode($res));
            Redis::set($key, $code);
            Redis::expire($key, Constant::CACHE_TTL_TWO_MINUTE);

            return true;
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            Log::error(__FUNCTION__ . ' ' . json_encode($e->results));
        }

        return false;
    }

    public function check($mobile, $code)
    {
        $key   = sprintf(Constant::AUTH_SMS_CODE, $mobile);
        $saved = Redis::get($key);
        Log::debug(__FUNCTION__ . ' key: ' . $key . ' code:' . $code . ' saved:' . $saved);
        if ($saved === $code) {
            return true;
        }

        return false;
    }
}
