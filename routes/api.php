<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'namespace'  => 'App\Http\Controllers\Api',
    'middleware' => [
        'cors',
        'api.throttle',
    ],
], function (\Dingo\Api\Routing\Router $api) {
    /**
     * 无需认证的接口
     */
    // 公有-认证
    $api->group(['prefix' => '/auth'], function (\Dingo\Api\Routing\Router $api) {
        $api->get('captcha', 'Auth\CaptchaController@getCode');
        $api->get('sms-code', ['uses' => 'Auth\SmsCodeController@getCode', 'expires' => env('SMS_CODE_EXPIRES_MINUTE', 1), 'limit' => env('SMS_CODE_LIMIT', 1)]);
    });

    // 用户认证
    $api->get('user/auth/sms-code', ['uses' => 'User\AuthController@getSmsCode', 'expires' => env('SMS_CODE_EXPIRES_MINUTE', 1), 'limit' => env('SMS_CODE_LIMIT', 1)]);
    $api->group(['prefix' => '/user/auth', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'User\AuthController@login');
        $api->post('register', 'User\AuthController@register');
        $api->post('login-by-sms-code', 'User\AuthController@loginBySmsCode');
    });

    // 管理员认证
    $api->group(['prefix' => '/admin/auth', 'middleware' => 'admin', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'Admin\AuthController@login');
        $api->post('register', 'Admin\AuthController@register');
        $api->post('login-by-sms-code', 'Admin\AuthController@loginBySmsCode');
    });

    // 运营认证
    $api->group(['prefix' => '/ops/auth', 'middleware' => 'ops', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'Ops\AuthController@login');
        $api->post('register', 'Ops\AuthController@register');
        $api->post('login-by-sms-code', 'Ops\AuthController@loginBySmsCode');
    });

    // 公众号授权
    $api->group(['prefix' => 'wechat'], function (\Dingo\Api\Routing\Router $api) {
        $api->post('serve', 'User\OpenPlatform\WeChat\OpenPlatformController@serve');
        $api->get('bind/callback', 'User\OpenPlatform\WeChat\OpenPlatformController@bindCallBack');
        $api->post('message/{appId}', 'User\OpenPlatform\WeChat\OpenPlatformController@message');
    });

    /**
     * 需认证但不需要刷新token的接口
     */
    $api->get('/user/auth/logout', ['middleware' => 'api.auth', 'expires' => 1, 'limit' => 60, 'uses' => 'User\AuthController@logout']);
    $api->get('/admin/auth/logout', ['middleware' => ['admin', 'api.auth'], 'expires' => 1, 'limit' => 60, 'uses' => 'Admin\AuthController@logout']);
    $api->get('/ops/auth/logout', ['middleware' => ['ops', 'api.auth'], 'expires' => 1, 'limit' => 60, 'uses' => 'Ops\AuthController@logout']);

    /**
     * 需认证且刷新token的接口
     */
    // 用户端
    $api->group(['middleware' => ['refresh', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        // 个人中心
        $api->group(['prefix' => '/user/auth'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('me', 'User\AuthController@me');
            $api->put('rename', 'User\UserController@resetName');
            $api->get('sms-code', 'Auth\SmsCodeController@getCodeByCode');
            $api->put('remobile', 'User\UserController@resetMobile');
            $api->put('repwd', 'User\UserController@resetPassword');
        });

        // 权限
        $api->group(['prefix' => '/role'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'Permission\RoleController@index');
            $api->post('create', 'Permission\RoleController@create');
            $api->post('assign/{role}/{user}', 'Permission\RoleController@assignRole');
            $api->delete('remove/{role}/{user}', 'Permission\RoleController@removeRole');
            $api->get('my/all', 'Permission\RoleController@allMyRoles');
        });
        $api->group(['prefix' => '/permission'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'Permission\PermissionController@index');
            $api->post('create', 'Permission\PermissionController@create');
            $api->post('assign/{permission}/{role}', 'Permission\PermissionController@assignRole');
            $api->delete('remove/{permission}/{role}', 'Permission\PermissionController@removeRole');
            $api->get('my/all', 'Permission\PermissionController@allMyPermissons');
        });

        // 公众号管理
        $api->get('open/wechat/binding', 'User\OpenPlatform\WeChat\OpenPlatformController@binding');
        $api->group(['prefix' => 'open/wechat/apps'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'User\OpenPlatform\WeChat\AppManageController@index');
            $api->get('switch', 'User\OpenPlatform\WeChat\AppManageController@switch');
            $api->get('unbind', 'User\OpenPlatform\WeChat\AppManageController@unbind');
        });

        // 站内信
        $api->resource('mail', 'Message\MailController');
        $api->get('mail/unread/count', 'Message\MailController@unreadCount');
        $api->get('mail/unread/allread', 'Message\MailController@setAllRead');

        // 关键词回复规则
        $api->resource('open/rule', 'User\OpenPlatform\AutoReply\RuleController');
        $api->group(['prefix' => 'open/rules'], function (\Dingo\Api\Routing\Router $api) {
            // 任意回复规则
            $api->post('any', 'User\OpenPlatform\AutoReply\AnyController@store');
            $api->get('any', 'User\OpenPlatform\AutoReply\AnyController@show');
            // 关注回复规则
            $api->post('subscribe', 'User\OpenPlatform\AutoReply\SubscribeController@store');
            $api->get('subscribe', 'User\OpenPlatform\AutoReply\SubscribeController@show');
        });

        // 超级二维码
        $api->resource('open/qrcode', 'User\OpenPlatform\QrCode\WeQrcodeController');

        // 自定义菜单 // 个性化菜单
        $api->resource('open/menu', 'User\OpenPlatform\Menu\MenuController');

        // 素材库
        $api->resource('open/material/news', 'User\OpenPlatform\Material\NewsController');
        $api->resource('open/material/image', 'User\OpenPlatform\Material\ImageController');
        $api->resource('open/material/voice', 'User\OpenPlatform\Material\VoiceController');
        $api->resource('open/material/video', 'User\OpenPlatform\Material\VideoController');
        $api->resource('open/material/thumb', 'User\OpenPlatform\Material\ThumbController');

        // 实体店铺管理
        $api->resource('shop', 'User\Shop\ShopController');
        $api->resource('project', 'User\Shop\ProjectController');
    });

    // 管理后台
    $api->group(['middleware' => ['admin', 'refresh', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/admin/auth/me', 'Admin\AuthController@me');
    });

    // 运营后台
    $api->group(['middleware' => ['ops', 'refresh', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/ops/auth/me', 'Ops\AuthController@me');
    });
});
