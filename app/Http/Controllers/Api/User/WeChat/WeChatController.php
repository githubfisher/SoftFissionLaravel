<?php
namespace App\Http\Controllers\Api\User\WeChat;

use Log;
use EasyWeChat\Factory;
use App\Http\Utilities\Constant;
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
                $msgType = $message['MsgType'];
                $message['fansInfo'] = $fansInfo;
                $message['appInfo']  = $appInfo;
                switch ($msgType) {
                    case 'event':
                        $this->logMsg($message, Constant::FLASE_ZERO);

                        break;
                    case 'text':
                        $this->logMsg($message, Constant::FLASE_ZERO);

                        break;
                    default:
                        $this->logMsg($message, Constant::FLASE_ZERO);

                        break;
                }

                return '深蓝的天空中挂着一轮金黄的圆月，下面是海边的沙地，都种着一望无际的碧绿的西瓜。其间①有一个十一二岁的少年，项带银圈，手捏一柄钢叉，向一匹猹②尽力地刺去。那猹却将身一扭，反从他的胯下逃走了。这少年便是闰土。我认识他时，也不过十多岁，离现在将有三十年了；那时我的父亲还在世，家景也好，我正是一个少爷。那一年，我家是一件大祭祀的值年。这祭祀，说是三十多年才能轮到一回，所以很郑重。正月里供像，供品很多，祭器很讲究，拜的人也很多，祭器也很要防偷去。我家只有一个忙月（我们这里给人做工的分三种：整年给一定人家做工的叫长工；按日给人做工的叫短工；自己也种地，只在过年过节以及收租时候来给一定的人家做工的称忙月），忙不过来，他便对父亲说，可以叫他的儿子闰土来管祭器的。我的父亲允许了；我也很高兴，因为我早听到闰土这名字，而且知道他和我仿佛年纪，闰月生的，五行缺土，所以他的父亲叫他闰土。他是能装弶捉小鸟雀的。我于是日日盼望新年，新年到，闰土也就到了。好容易到了年末，有一日，母亲告诉我，闰土来了，我便飞跑地去看。他正在厨房里，紫色的圆脸，头戴一顶小毡帽，颈上套一个明晃晃的银项圈，这可见他的父亲十分爱他，怕他死去，所以在神佛面前许下愿心，用圈子将他套住了。深蓝的天空中挂着一轮金黄的圆月，下面是海边的沙地，都种着一望无际的碧绿的西瓜。其间①有一个十一二岁的少年，项带银圈，手捏一柄钢叉，向一匹猹②尽力地刺去。那猹却将身一扭，反从他的胯下逃走了。这少年便是闰土。我认识他时，也不过十多岁，离现在将有三十年了；那时我的父亲还在世，家景也好，我正是一个少爷。那一年，我家是一件大祭祀的值';
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
