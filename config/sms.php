<?php

return [
    'scene' => [
        // 注册
        'register' => [
            'template_id' => 224794,
            'cache_ttl'   => env('SMS_CODE_TTL', 60),
        ],
        // 登录
        'login' => [
            'template_id' => 224794,
            'cache_ttl'   => env('SMS_CODE_TTL', 60),
        ],
        // 更新手机号
        'reset' => [
            'template_id' => 224794,
            'cache_ttl'   => env('SMS_CODE_TTL', 60),
        ],
        // 身份验证
        'auth' => [
            'template_id' => 224794,
            'cache_ttl'   => env('SMS_CODE_TTL', 60),
        ],
        // 绑定
        'bind' => [
            'template_id' => 224794,
            'cache_ttl'   => env('SMS_CODE_TTL', 60),
        ],
    ],
];
