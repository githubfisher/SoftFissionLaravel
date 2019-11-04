<?php
/**
 * Created by PhpStorm.
 * User: fisher
 * Date: 2019-03-20
 * Time: 14:37
 */

return [
    'wechat' => [
        'app_id'  => env('APPID', 'wx3d96da47302d543c'),
        'secret'  => env('APPSECRET', 'd423c705299ece2f7ff9a7621ea10a2c'),
        'token'   => env('APPTOKEN', 'huoyanjinjing'),
        'aes_key' => env('AESKEY', '1caohongfang2jiangliulei3suwenbin4renxf5lid'),
        'oauth'   => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => env('API_DOMAIN', 'http://api.playhudong.com') . '/wechat/authcallback',
        ],
        'debug'         => env('APP_DEBUG', false),
        'response_type' => 'array',
        'log'           => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => storage_path('logs/') . 'easywechat-' . date('Y-m-d') . '.log',
        ],
    ],
];
