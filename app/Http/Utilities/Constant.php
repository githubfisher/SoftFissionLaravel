<?php
namespace App\Http\Utilities;

class Constant
{
    // 缓存时效
    const CACHE_TTL_MINUTE      = 60;
    const CACHE_TTL_TWO_MINUTE  = 120;
    const CACHE_TTL_FIVE_MINUTE = 300;
    // Base64图片头
    const BASE64_PREFIX_PNG = 'data:image/png;base64,';
    // 缓存短信码 // scene:mobile
    const AUTH_SMS_SEND           = 'sf:auth:sms_code:%s:%s';
    // 短信验证码场景值
    const SMS_CODE_SCENE_REGISTER = 'register';
    const SMS_CODE_SCENE_LOGIN    = 'login';
}
