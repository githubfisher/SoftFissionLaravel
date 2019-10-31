<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Repositories\Sms;
use App\Http\Utilities\FeedBack;
use App\Http\Repositories\Captcha;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CaptchaCheckRequest;

class SmsCodeController extends Controller
{
    public function getCode(CaptchaCheckRequest $request, Captcha $captcha, Sms $sms)
    {
        if ($captcha->check($request->input('captcha'), $request->input('key'))) {
            $mobile = $request->input('mobile');
            if ( ! $sms->hasSent($mobile)) {
                $res = $sms->sendCode($mobile);

                return $res ? $this->suc() : $this->err(FeedBack::SMS_CODE_SEND_FAIL);
            }

            return $this->suc(FeedBack::SMS_CODE_HAS_SENT);
        }

        return $this->err(FeedBack::CAPTCHA_INCORRECT);
    }
}
