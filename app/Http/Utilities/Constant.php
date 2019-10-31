<?php
namespace App\Http\Utilities;

class Constant
{
    const CACHE_TTL_MINUTE      = 60;
    const CACHE_TTL_TWO_MINUTE  = 120;
    const CACHE_TTL_FIVE_MINUTE = 300;

    const BASE64_PREFIX_PNG = 'data:image/png;base64,';

    // 缓存短信码 // mobile
    public const AUTH_SMS_CODE  = 'sf:auth:sms_code:%s';
}
