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
    $api->group(['prefix' => '/user/auth', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'User\AuthController@login');
        $api->post('register', 'User\AuthController@register');
        $api->post('login-by-sms-code', 'User\AuthController@loginBySmsCode');
    });

    $api->group(['prefix' => '/admin/auth', 'middleware' => 'admin', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'Admin\AuthController@login');
        $api->post('register', 'Admin\AuthController@register');
        $api->post('login-by-sms-code', 'Admin\AuthController@loginBySmsCode');
    });

    $api->group(['prefix' => '/ops/auth', 'middleware' => 'ops', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'Ops\AuthController@login');
        $api->post('register', 'Ops\AuthController@register');
        $api->post('login-by-sms-code', 'Ops\AuthController@loginBySmsCode');
    });

    $api->group(['prefix' => '/auth'], function (\Dingo\Api\Routing\Router $api) {
        $api->get('captcha', 'Auth\CaptchaController@getCode');
        $api->get('sms-code', ['uses' => 'Auth\SmsCodeController@getCode', 'expires' => 1, 'limit' => 1]);
    });

    $api->group(['prefix' => 'wechat'], function (\Dingo\Api\Routing\Router $api) {
        $api->post('serve', 'User\WeChat\WeChatController@serve');
        $api->post('bind/callback', 'User\WeChat\WeChatController@bindCallBack');
    });

    /**
     * 需认证但不需要刷新token的接口
     */
    $api->group(['middleware' => 'api.auth', 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/user/auth/logout', 'User\AuthController@logout');
    });

    $api->group(['middleware' => ['admin', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/admin/auth/logout', 'Admin\AuthController@logout');
    });

    $api->group(['middleware' => ['ops', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/ops/auth/logout', 'Ops\AuthController@logout');
    });

    /**
     * 需认证且刷新token的接口
     */
    $api->group(['middleware' => ['refresh', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->group(['prefix' => '/user/auth'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('me', 'User\AuthController@me');
            $api->put('rename', 'User\UserController@resetName');
            $api->get('sms-code', 'Auth\SmsCodeController@getCodeByCode');
            $api->put('remobile', 'User\UserController@resetMobile');
            $api->put('repwd', 'User\UserController@resetPassword');
        });
        $api->group(['prefix' => '/role'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'Permission\RoleController@index');
            $api->post('create', 'Permission\RoleController@create');
        });
        $api->group(['prefix' => '/permission'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'Permission\PermissionController@index');
            $api->post('create', 'Permission\PermissionController@create');
        });
        $api->get('/wechat/binding', 'User\WeChat\WeChatController@binding');
        $api->group(['prefix' => '/wechat/apps'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'User\WeChat\ManageController@index');
            $api->get('switch', 'User\WeChat\ManageController@switchApp');
            $api->get('unbind', 'User\WeChat\ManageController@unbind');
        });
        $api->group(['prefix' => '/mail'], function (\Dingo\Api\Routing\Router $api) {
            $api->get('', 'User\Message\MailController@index');
            $api->get('unread', 'User\Message\MailController@unread');
            $api->put('read', 'User\Message\MailController@setRead');
        });
    });

    $api->group(['middleware' => ['admin', 'refresh', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/admin/auth/me', 'Admin\AuthController@me');
    });

    $api->group(['middleware' => ['ops', 'refresh', 'api.auth'], 'expires' => 1, 'limit' => 60], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/ops/auth/me', 'Ops\AuthController@me');
    });
});
