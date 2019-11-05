<?php
namespace App\Http\Utilities;

class FeedBack
{
    const CAPTCHA_INCORRECT   = ['code' => 1000, 'message' => 'captcha incorrect!'];
    const SMS_CODE_HAS_SENT   = ['code' => 1001, 'message' => 'sms code has sent!'];
    const SMS_CODE_SEND_FAIL  = ['code' => 1002, 'message' => 'sms code send fail!'];
    const SMS_CODE_INCORRECT  = ['code' => 1003, 'message' => 'sms code incorrect!'];
    const PASSWORD_INCORRECT  = ['code' => 1004, 'message' => 'password incorrect!'];
    const PASSWORD_RESET_FAIL = ['code' => 1005, 'message' => 'password reset fail!'];
    const USERNAME_RESET_FAIL = ['code' => 1006, 'message' => 'name reset fail!'];
    const MOBILE_RESET_FAIL   = ['code' => 1007, 'message' => 'mobile reset fail!'];
    const SAME_MOBILE         = ['code' => 1008, 'message' => 'same mobile!'];
    const BIND_FAIL_BOUND     = ['code' => 1009, 'message' => 'bound!'];
    const BIND_FAIL           = ['code' => 1010, 'message' => 'bind fail!'];
    const SWITCH_FAIL         = ['code' => 1011, 'message' => 'switch fail!'];
}
