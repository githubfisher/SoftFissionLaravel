<?php
namespace App\Http\Utilities;

class FeedBack
{
    const CAPTCHA_INCORRECT  = ['code' => 1000, 'message' => 'captcha incorrect!'];
    const SMS_CODE_HAS_SENT  = ['code' => 1001, 'message' => 'sms code has sent!'];
    const SMS_CODE_SEND_FAIL = ['code' => 1002, 'message' => 'sms code send fail!'];
    const SMS_CODE_INCORRECT = ['code' => 1003, 'message' => 'sms code incorrect!'];
}
