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
    'limit'   => 60,
    'expires' => 1,
], function (\Dingo\Api\Routing\Router $api) {

    /**
     * 无需认证的接口
     */
    $api->group(['prefix' => '/user/auth'], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'User\AuthController@login');
        $api->post('register', 'User\AuthController@register');
    });

    $api->group(['prefix' => '/admin/auth', 'middleware' => 'admin'], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'Admin\AuthController@login');
        $api->post('register', 'Admin\AuthController@register');
    });

    $api->group(['prefix' => '/ops/auth', 'middleware' => 'ops'], function (\Dingo\Api\Routing\Router $api) {
        $api->post('login', 'Ops\AuthController@login');
        $api->post('register', 'Ops\AuthController@register');
    });

    $api->group(['prefix' => '/auth'], function (\Dingo\Api\Routing\Router $api) {
        $api->get('captcha', 'Auth\CaptchaController@getCode');
        $api->get('sms-code', ['middleware' => 'api.throttle', 'limit' => 1, 'expires' => 2, 'uses' => 'Auth\SmsCodeController@getCode']);
        $api->get('e-code', 'Auth\EmailCodeController@getCode');
    });

    /**
     * 需认证但不需要刷新token的接口
     */
    $api->group(['middleware' => 'api.auth'], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/user/auth/logout', 'User\AuthController@logout');
    });

    $api->group(['middleware' => ['admin', 'api.auth']], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/admin/auth/logout', 'Admin\AuthController@logout');
    });

    $api->group(['middleware' => ['ops', 'api.auth']], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/ops/auth/logout', 'Ops\AuthController@logout');
    });

    /**
     * 需认证且刷新token的接口
     */
    $api->group(['middleware' => ['refresh', 'api.auth']], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/user/auth/me', 'User\AuthController@me');
    });

    $api->group(['middleware' => ['admin', 'refresh', 'api.auth']], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/admin/auth/me', 'Admin\AuthController@me');
    });

    $api->group(['middleware' => ['ops', 'refresh', 'api.auth']], function (\Dingo\Api\Routing\Router $api) {
        $api->get('/ops/auth/me', 'Ops\AuthController@me');
    });
});
