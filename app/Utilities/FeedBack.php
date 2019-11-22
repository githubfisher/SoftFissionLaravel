<?php
namespace App\Utilities;

class FeedBack
{
    const CAPTCHA_INCORRECT         = ['code' => 1000, 'message' => 'captcha incorrect!'];
    const SMS_CODE_HAS_SENT         = ['code' => 1001, 'message' => 'sms code has sent!'];
    const SMS_CODE_SEND_FAIL        = ['code' => 1002, 'message' => 'sms code send fail!'];
    const SMS_CODE_INCORRECT        = ['code' => 1003, 'message' => 'sms code incorrect!'];
    const PASSWORD_INCORRECT        = ['code' => 1004, 'message' => 'password incorrect!'];
    const PASSWORD_RESET_FAIL       = ['code' => 1005, 'message' => 'password reset fail!'];
    const USERNAME_RESET_FAIL       = ['code' => 1006, 'message' => 'name reset fail!'];
    const MOBILE_RESET_FAIL         = ['code' => 1007, 'message' => 'mobile reset fail!'];
    const SAME_MOBILE               = ['code' => 1008, 'message' => 'same mobile!'];
    const BIND_FAIL_BOUND           = ['code' => 1009, 'message' => 'bound!'];
    const BIND_FAIL                 = ['code' => 1010, 'message' => 'bind fail!'];
    const SWITCH_FAIL               = ['code' => 1011, 'message' => 'switch fail!'];
    const WECHAT_APP_NOT_FOUND      = ['code' => 1012, 'message' => 'wechat app not found!'];
    const CREATE_FAIL               = ['code' => 1013, 'message' => 'create fail!'];
    const UPDATE_FAIL               = ['code' => 1016, 'message' => 'update fail!'];
    const PARAMS_INCORRECT          = ['code' => 1014, 'message' => 'params incorrect!'];
    const RULE_NOT_FOUND            = ['code' => 1015, 'message' => 'rule not found!'];
    const MATERIAL_NEWS_NOT_FOUND   = ['code' => 1017, 'message' => 'material news not found!'];
    const MATERIAL_NEWS_CANNOT_DEL  = ['code' => 1018, 'message' => 'material news can not delete!'];
    const MATERIAL_IMAGE_NOT_FOUND  = ['code' => 1019, 'message' => 'material image not found!'];
    const MATERIAL_IMAGE_CANNOT_DEL = ['code' => 1020, 'message' => 'material image can not delete!'];
    const MATERIAL_VIDEO_NOT_FOUND  = ['code' => 1021, 'message' => 'material video not found!'];
    const MATERIAL_VIDEO_CANNOT_DEL = ['code' => 1022, 'message' => 'material video can not delete!'];
    const MATERIAL_VOICE_NOT_FOUND  = ['code' => 1023, 'message' => 'material voice not found!'];
    const MATERIAL_VOICE_CANNOT_DEL = ['code' => 1024, 'message' => 'material voice can not delete!'];
    const MATERIAL_THUMB_NOT_FOUND  = ['code' => 1025, 'message' => 'material thumb not found!'];
    const MATERIAL_THUMB_CANNOT_DEL = ['code' => 1026, 'message' => 'material thumb can not delete!'];
    const REGISTER_FAIL             = ['code' => 1027, 'message' => 'register fail!'];
    const USER_NOT_FOUND            = ['code' => 1028, 'message' => 'user not found!'];
    const DELETE_FAIL               = ['code' => 1029, 'message' => 'delete fail!'];
}
