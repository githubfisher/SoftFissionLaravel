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
    const CACHE_TTL_TEN_MINUTE  = 600;
    const CACHE_TTL_ONE_HOUR    = 3600;
    const CACHE_TTL_ONE_DAY     = 86400;
    const CACHE_TTL_THIRTY_DAY  = 2592000;
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
    const BIND_APP_INFO = 'sf:bind_app_info:%s';
    // 站内信-未读数 // guard userID
    const MAIL_UNREAD   = 'sf:mail_unread:%s:%d';
    // 站内信模板
    const MAIL_TEMPLATE = [];
    // 微信消息类型
    const MSG_TYPE_EVENT = 1;
    const MSG_TYPE_TEXT  = 2;
    const MSG_TYPE_OTHER = 0;
    // 自动回复, 场景值
    const REPLY_RULE_SCENE_KEYWORD   = 'keyword';
    const REPLY_RULE_SCENE_SCAN      = 'scan';
    const REPLY_RULE_SCENE_CLICK     = 'click';
    const REPLY_RULE_SCENE_SUBSCRIBE = 'subscribe';
    const REPLY_RULE_SCENE_ANY       = 'any';
    // 二维码类型
    const QR_CODE_TYPE_SHORT_TERM = 1;
    const QR_CODE_TYPE_FOREVER    = 2;
    // 临时二维码到期计算方式
    const QR_CODE_SHORT_TERM_BY_EXPIRE = 1;
    const QR_CODE_SHORT_TERM_BY_DATE   = 2;
}
