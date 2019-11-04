<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Repositories\Sms;
use App\Http\Utilities\Constant;
use App\Http\Utilities\FeedBack;
use App\Http\Repositories\Captcha;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CaptchaCheckRequest;
use App\Http\Requests\Auth\SmsCodeCheckRequest;

class SmsCodeController extends Controller
{
    /**
     * 验证图形验证码, 发送短信验证码
     *
     * @param CaptchaCheckRequest $request
     * @param Captcha             $captcha
     * @param Sms                 $sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCode(CaptchaCheckRequest $request, Captcha $captcha, Sms $sms)
    {
        if ($captcha->check($request->input('captcha'), $request->input('key'))) {
            $mobile = $request->input('mobile');
            $scene  = $request->input('scene');
            if ( ! $sms->hasSent($mobile, $scene)) {
                $res = $sms->sendCode($mobile, $scene);

                return $res ? $this->suc() : $this->err(FeedBack::SMS_CODE_SEND_FAIL);
            }

            return $this->suc(FeedBack::SMS_CODE_HAS_SENT);
        }

        return $this->err(FeedBack::CAPTCHA_INCORRECT);
    }

    /**
     * 验证短信验证码, 发送短信验证码 (仅用于更换手机号时使用)
     *
     * @param SmsCodeCheckRequest $request
     * @param Sms                 $sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCodeByCode(SmsCodeCheckRequest $request, Sms $sms)
    {
        $mobile = $request->input('mobile');
        if ($this->user()->mobile != $mobile) {
            $scene = Constant::SMS_CODE_SCENE_RESET;
            if ($sms->check($this->user()->mobile, $request->input('code'), $scene)) {
                if ( ! $sms->hasSent($mobile, $scene)) {
                    $res = $sms->sendCode($mobile, $scene);

                    return $res ? $this->suc() : $this->err(FeedBack::SMS_CODE_SEND_FAIL);
                }

                return $this->suc(FeedBack::SMS_CODE_HAS_SENT);
            }

            return $this->err(FeedBack::SMS_CODE_INCORRECT);
        }

        return $this->err(FeedBack::SMS_CODE_INCORRECT);
    }
}
