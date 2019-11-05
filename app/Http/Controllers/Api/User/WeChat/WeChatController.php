<?php
namespace App\Http\Controllers\Api\User\WeChat;

use Log;
use EasyWeChat\Factory;
use App\Http\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Repositories\WeChatApp\App;
use EasyWeChat\OpenPlatform\Server\Guard;
use App\Http\Requests\User\WeChat\BindRequest;

class WeChatController extends Controller
{
    protected $openPlatform;

    public function __construct()
    {
        $this->openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
    }

    public function serve(App $apps)
    {
        $server = $this->openPlatform->server;
        // 处理授权成功事件
        $server->push(function ($message) {
            Log::debug('Authorized ' . json_encode($message));
        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            Log::debug('UpdateAuthorized ' . json_encode($message));
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) use ($apps) {
            Log::debug('Unauthorized ' . json_encode($message));

            // 删除公众号信息 // 加入到已解绑公众号集合中
            $apps->unbind($message['AuthorizerAppid']);
        }, Guard::EVENT_UNAUTHORIZED);

        // 处理VerifyTicket推送事件
        $server->push(function ($message) {
            Log::debug('VerifyTicket ' . json_encode($message));
        }, Guard::EVENT_COMPONENT_VERIFY_TICKET);

        return $server->serve();
    }

    public function binding(BindRequest $request)
    {
        $domain = config('api.domain');
        if ( ! empty($request->get('is_mobile'))) {
            $url = $this->openPlatform->getMobilePreAuthorizationUrl($domain . '/wechat/bind/callback?is_mobile=1&user_id=' . $this->user()->id, ['auth_type' => 3]);
        } else {
            $url = $this->openPlatform->getPreAuthorizationUrl($domain . '/wechat/bind/callback?is_mobile=0&user_id=' . $this->user()->id);
        }

        $html = <<<EOF
<html>
    <body></body>
    <script>window.location.href='$url';</script>
</html>
EOF;

        return response()->make($html);
    }

    public function bindCallBack(BindRequest $request, App $apps)
    {
        $oAuth       = $this->openPlatform->handleAuthorize();
        $appId       = $oAuth['authorization_info']['authorizer_appid'];
        $app         = $apps->first($appId);
        $frontDomain = config('front.url');
        //判断是否被绑定
        $userId   = $request->get('user_id');
        if ($app && $app->user_id != $userId) {
            Log::debug(__FUNCTION__ . ' ' . $appId . ', 该公众号已绑定到其他账号! userid: ' . $app->user_id);

            return redirect($frontDomain . '/#/bind/fail?message=' . FeedBack::BIND_FAIL_BOUND['message']);
        }

        $info    = $this->openPlatform->getAuthorizer($oAuth['authorization_info']['authorizer_appid']);
        $appInfo = [
            'app_id'            => $appId,
            'user_id'           => $userId,
            'refresh_token'     => $oAuth['authorization_info']['authorizer_refresh_token'],
            'nick_name'         => $info['authorizer_info']['nick_name'],
            'head_img'          => $info['authorizer_info']['head_img'] ?? '',
            'user_name'         => $info['authorizer_info']['user_name'],
            'alias'             => (int) $info['authorizer_info']['alias'],
            'qrcode_url'        => $info['authorizer_info']['qrcode_url'],
            'principal_name'    => $info['authorizer_info']['principal_name'],
            'signature'         => $info['authorizer_info']['signature'],
            'service_type_info' => $info['authorizer_info']['service_type_info']['id'],
            'verify_type_info'  => $info['authorizer_info']['verify_type_info']['id'],
            'deleted_at'        => null,
            'keyword_reply'     => 0,
            'anytype_reply'     => 0,
            'subscribe_reply'   => 0,
        ];
        $id = $apps->updateOrCreate($appId, $appInfo);
        if ($id) {
            $appInfo['id'] = $id;
            // 更新绑定公众号列表
            $apps->refreshAppList($userId, $appInfo);
            $apps->refreshAppInfo($appInfo);

            // 若之前解绑过, 从已解绑集合中去除
            $apps->remUnbindSet($appId);

            return redirect($frontDomain . '/#/bind/success');
        }
        Log::error(__FUNCTION__ . ' ' . $appId . ', 更新或创建公众号信息失败! userId: ' . $userId);

        return redirect($frontDomain . '/#/bind/fail?message=' . FeedBack::BIND_FAIL['message']);
    }
}
