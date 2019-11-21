<?php
namespace App\Http\Controllers\Api\User\WeChat;

use Log;
use EasyWeChat\Factory;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Http\Repositories\WeChatApp\App;
use EasyWeChat\OpenPlatform\Server\Guard;
use App\Http\Requests\User\WeChat\BindRequest;
use App\Repositories\WeChat\WeAppRepositoryEloquent;

class OpenPlatformController extends Controller
{
    protected $openPlatform;

    public function __construct()
    {
        $this->openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
    }

    public function serve(WeAppRepositoryEloquent $repository)
    {
        $server = $this->openPlatform->server;

        // 处理授权更新事件
        $server->push(function ($message) use ($repository) {
            Log::debug('UpdateAuthorized ' . json_encode($message));

            $app = $repository->where('app_id', $message['AuthorizerAppid'])->first();
            if ($app) {
                $authorizer = $this->openPlatform->getAuthorizer($message['AuthorizerAppid']);
                $appInfo = [
                    'nick_name'          => $authorizer['authorizer_info']['nick_name'],
                    'head_img'           => $authorizer['authorizer_info']['head_img'] ?? '',
                    'user_name'          => $authorizer['authorizer_info']['user_name'],
                    'alias'              => $authorizer['authorizer_info']['alias'],
                    'qrcode_url'         => $authorizer['authorizer_info']['qrcode_url'],
                    'principal_name'     => $authorizer['authorizer_info']['principal_name'],
                    'signature'          => $authorizer['authorizer_info']['signature'],
                    'service_type_info'  => $authorizer['authorizer_info']['service_type_info']['id'],
                    'verify_type_info'   => $authorizer['authorizer_info']['verify_type_info']['id'],
                    'deleted_at'         => null,
                    'funcscope_category' => isset($authorizer['authorization_info']['func_info']) ? Arr::sort(Arr::pluck($authorizer['authorization_info']['func_info'], 'funcscope_category.id')) : '[]',
                ];
                $repository->update($appInfo, $app->id);
                $repository->refreshAppList($app->user_id);
                $repository->refreshAppInfo($message['AuthorizerAppid']);
                $repository->remUnbindSet($message['AuthorizerAppid']);
            } else {
                Log::warning('APPID not found! ' . $message['AuthorizerAppid']);
            }
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) use ($repository) {
            Log::debug('Unauthorized ' . json_encode($message));

            $repository->unbind($message['AuthorizerAppid']);
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

    public function bindCallBack(BindRequest $request, WeAppRepositoryEloquent $repository)
    {
        $oAuth       = $this->openPlatform->handleAuthorize();
        $appId       = $oAuth['authorization_info']['authorizer_appid'];
        $app         = $repository->where('app_id', $appId)->first();
        $frontDomain = config('front.url');
        $userId      = $request->get('user_id');
        if ($app && $app->user_id != $userId) {
            Log::debug(__FUNCTION__ . ' ' . $appId . ', 该公众号已绑定到其他账号! userid: ' . $app->user_id);

            return redirect($frontDomain . '/#/bind/fail?message=' . FeedBack::BIND_FAIL_BOUND['message']);
        }

        $info    = $this->openPlatform->getAuthorizer($oAuth['authorization_info']['authorizer_appid']);
        $appInfo = [
            'user_id'            => $userId,
            'app_id'             => $appId,
            'refresh_token'      => $oAuth['authorization_info']['authorizer_refresh_token'],
            'nick_name'          => $info['authorizer_info']['nick_name'],
            'head_img'           => $info['authorizer_info']['head_img'] ?? '',
            'user_name'          => $info['authorizer_info']['user_name'],
            'alias'              => $info['authorizer_info']['alias'],
            'qrcode_url'         => $info['authorizer_info']['qrcode_url'],
            'principal_name'     => $info['authorizer_info']['principal_name'],
            'signature'          => $info['authorizer_info']['signature'],
            'service_type_info'  => $info['authorizer_info']['service_type_info']['id'],
            'verify_type_info'   => $info['authorizer_info']['verify_type_info']['id'],
            'deleted_at'         => null,
            'funcscope_category' => isset($oAuth['authorization_info']['func_info']) ? Arr::sort(Arr::pluck($oAuth['authorization_info']['func_info'], 'funcscope_category.id')) : '[]',
        ];
        if ($res = $repository->updateOrCreate(['app_id' => $appId], $appInfo)) {
            // 更新绑定公众号列表
            $repository->refreshAppList($userId);
            $repository->refreshAppInfo($appId);
            // 若之前解绑过, 从已解绑集合中去除
            $repository->remUnbindSet($appId);
            // TODO
            // 发送绑定成功的消息
            // 甄别赠送体验优惠券
            // 推送教程|案例

            return redirect($frontDomain . '/#/bind/success');
        }
        Log::error(__FUNCTION__ . ' ' . $appId . ', 更新或创建公众号信息失败! userId: ' . $userId);

        return redirect($frontDomain . '/#/bind/fail?message=' . FeedBack::BIND_FAIL['message']);
    }

    public function message($appId, App $apps)
    {
        try {
            if (empty($appId)) {
                throw new \Exception('appId is empty!', 999);
            }

            // 判断公众号是否已解绑(1灵猴后台解绑, 2微信公众后台解绑)
            if ( ! $apps->isBinded($appId)) {
                return $this->success();
            }

            $appInfo = $apps->getAppInfo($appId);
            if (empty($appInfo)) {
                $apps->unbind($appId);

                return $this->success();
            }

            $message = $this->openPlatform->server->getMessage();
            // 微信认证的公众号获取粉丝信息
            $openId          = $message['FromUserName'];
            $officialAccount = $this->openPlatform->officialAccount($appInfo['app_id'], $appInfo['refresh_token']);
            $fansInfo        = [
                'openid'     => '',
                'nickname'   => '',
                'headimgurl' => '',
            ];
            if ($appInfo['verify_type_info'] >= Constant::FLASE_ZERO && ! empty($openId)) {
                $fans = $officialAccount->user->get($openId);
                if (isset($fans['errcode']) && ! empty($fans['errcode'])) {
                    Log::debug(__FUNCTION__ . ' 公众号未认证, 未能获取粉丝信息; 或获取粉丝信息失败. appid: ' . $appInfo['app_id'] . ' ' . json_encode($fans));
                } else {
                    $fansInfo = $fans;
                }
            }

            $server = $officialAccount->server;
            $server->push(function ($message) use ($officialAccount, $fansInfo, $appInfo) {
                $message['fansInfo'] = $fansInfo;
                $message['appInfo']  = $appInfo;
                $this->logMsg($message, Constant::FLASE_ZERO);

                return 'Long,long ago there lived a king.He loved horses. One day he asked an artist to draw him a beautiful horse. The artist said,“All right, but you must wait.” So the king waited. He waited and waited. At last, after a year he could not wait any longer. He went to see the artist himself.Quickly the artist brought out paper and a brush. In five minutes he finished drawing a very beautiful horse. The king was angry. “You can draw a good horse in five minutes,yet you kept me waiting for a year. Why?”“Come with me,please,” said the artist. They went to the artist’s workroom.. There the king saw piles and piles of paper. On every piece of paper was a picture of a horse. “It took me more than a year to learn to draw a beautiful horse in five minutes.” the artist said.Long,long ago there lived a king.He loved horses. One day he asked an artist to draw him a beautiful horse. The artist said,“All right, but you must wait.” So the king waited. He waited and waited. At last, after a year he could not wait any longer. He went to see the artist himself.Quickly the artist brought out paper and a brush. In five minutes he finished drawing a very beautiful horse. The king was angry. “You can draw a good horse in five minutes,yet you kept me waiting for a year. Why?”“Come with me,please,” said the artist. They went to the artist’s workroom.. There the king saw piles and piles of paper. On every piece of paper was a picture of a horse. “It took me more than a year to learn to draw a beautiful horse in five minutes.” the artist said.Long,long ago there lived a king.He loved horses. One day he asked an artist to draw him a beautiful horse. The artist said,“All right, but you must wait.” So the king waited. He waited and waited. At last, after a year he could not wait any longer. He went to see the artist himself.Quickly the artist brought out paper and a brush. In five minutes he finished drawing a very beautiful horse. The king was angry. “You can draw a good horse in five minutes,yet you kept m';
            });

            return response($server->serve()->getContent())->send();
        } catch (\Throwable $throwable) {
            $message = $throwable->getMessage();
            if (strpos($message, ':61023,') !== false) {
                $apps->unbind($appId);
                Log::debug(__FUNCTION__ . ' app: ' . $appId . ', refresh token invalid, 加入到已解绑公众号集合! ' . $throwable->getCode());
            } elseif (strpos($message, 'Invalid Signature') !== false || strpos($message, 'Invalid appId') !== false) {
                Log::error(__FUNCTION__ . ' ' . $message);
            } else {
                $data = [
                    'code'    => $throwable->getCode(),
                    'title'   => '微信消息处理异常',
                    'appid'   => $appId,
                    'message' => $message,
                    'file'    => $throwable->getFile(),
                    'line'    => $throwable->getLine(),
                    'trace'   => $throwable->getTrace(),
                ];
                Log::error(__FUNCTION__ . ' ' . json_encode($data));
            }
        }

        return $this->success();
    }

    /**
     * 返回给微信服务器, 成功接收并处理消息
     *
     * @author fisher
     * @date 2019-03-18 09:53
     */
    private function success()
    {
        return response('SUCCESS')->send();
    }

    // 记录消息信息入日志
    private function logMsg($message, $msgType = Constant::MSG_TYPE_OTHER)
    {
        $endfix = chr(27) . '[0m';
        // 昵称(openid)->APPID(公众号昵称)
        $nickname = isset($message['fansInfo']['nickname']) ? $message['fansInfo']['nickname'] : '';
        $str      = chr(27) . '[36m' . $nickname . $endfix . '(' . $message['FromUserName'] . ')->' . $message['appInfo']['app_id'] . '(' . chr(27) . '[35m' . $message['appInfo']['nick_name'] . $endfix . ')';

        // 事件或文本信息
        switch ($msgType) {
            case Constant::MSG_TYPE_EVENT:
                $subStr = isset($message['EventKey']) ? '(' . $message['EventKey'] . ') ' : '() ';
                $str    = $message['Event'] . $endfix . $subStr . $str;

                break;
            case Constant::MSG_TYPE_TEXT:
                $str = $message['Content'] . $endfix . ' ' . $str;

                break;
            default:
                $str = $message['MsgType'] . $endfix . ' ' . $str;
        }

        Log::debug(chr(27) . '[32m' . $str);
    }
}
