<?php
namespace App\Http\Utilities;

class Constant
{
    const FLASE_ZERO = 0;
    const TRUE_ONE   = 1;
    // 分页
    const PAGINATE_MIN    = 10;
    const PAGINATE_SMALL  = 20;
    const PAGINATE_MIDDLE = 50;
    const PAGINATE_MAX    = 100;
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
    const SMS_CODE_SCENE_RESET    = 'reset';
    // 已解绑的微信公众号appid的集合
    const UNBINDED_APP_ZSET       = 'sf:unbind_apps';
    // 绑定公众号列表 // userID
    const BIND_APP_LIST = 'sf:bind_app_list:%d';
    // 绑定公众号信息 // APPID
    const BIND_APP_INFO = 'lh:bind_app_info:%s';
}
