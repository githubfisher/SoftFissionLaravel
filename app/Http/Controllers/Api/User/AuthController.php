<?php
namespace App\Http\Controllers\Api\User;

use App\Notifications\ActivateMail;
use Auth;
use Carbon\Carbon;
use App\Services\Sms;
use App\Services\Captcha;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterByEmailRequest;
use App\Repositories\User\UserRepositoryEloquent;
use App\Http\Requests\Auth\RegisterBySmsCodeRequest;
use App\Http\Requests\User\Auth\LoginBySmsRequest;

class AuthController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 邮箱注册 todo 邮箱验证码
     *
     * @param RegisterByEmailRequest $request
     * @param UserRepositoryEloquent $repository
     * @param Captcha                $captcha
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function register(RegisterByEmailRequest $request, UserRepositoryEloquent $repository, Captcha $captcha)
    {
        if ($captcha->check($request->get('captcha'), $request->get('key'))) {
            $user = $repository->create(['email' => $request->get('email')]);
            if ($user) {
                // 发送激活邮件
                $user->notify(new ActivateMail($user));

                return $this->suc();
            }

            return $this->err(FeedBack::REGISTER_FAIL);
        }

        return $this->err(FeedBack::SMS_CODE_INCORRECT);
    }

    /**
     * @todo 邮件激活
     */
    public function activate()
    {
    }

    /**
     * 手机验证码登录/注册: 获取短信验证码;
     * 前端在获取验证码时不清楚用户是否已经注册;
     *
     * @param RegisterBySmsCodeRequest        $request
     * @param UserRepositoryEloquent $repository
     * @param Captcha                $captcha
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSmsCode(RegisterBySmsCodeRequest $request, UserRepositoryEloquent $repository, Captcha $captcha)
    {
        if ($captcha->check($request->get('captcha'), $request->get('key'))) {
            $mobile = $request->input('mobile');
            $user   = $repository->firstOrCreate(['mobile' => $mobile]);
            if ($user) {
                // 发送短信验证码
                $sms   = new Sms();
                $scene = empty($user->mobile_verified_at) ? Constant::SMS_CODE_SCENE_REGISTER : Constant::SMS_CODE_SCENE_LOGIN;
                if ( ! $sms->hasSent($mobile, $scene)) {
                    $res = $sms->sendCode($mobile, $scene);

                    return $res ? $this->suc() : $this->err(FeedBack::SMS_CODE_SEND_FAIL);
                }

                return $this->suc();
            }

            return $this->err(FeedBack::REGISTER_FAIL);
        }

        return $this->err(FeedBack::CAPTCHA_INCORRECT);
    }

    /**
     * 登录: 手机号(邮箱) + 密码
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if ($request->has('mobile')) {
            $credentials = $request->only(['mobile', 'password']);
        } else {
            $credentials = $request->only(['email', 'password']);
        }

        return (($token = Auth::attempt($credentials))
            ? $this->suc(['token' => $token], 201)
            : $this->err(['message' => '账号或密码错误'], 401));
    }

    /**
     * 登录/注册: 手机号+验证码
     *
     * @param LoginBySmsRequest      $request
     * @param UserRepositoryEloquent $repository
     * @param Sms                    $sms
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function loginBySmsCode(LoginBySmsRequest $request, UserRepositoryEloquent $repository, Sms $sms)
    {
        $mobile = $request->get('mobile');
        $pass   = false;
        $scenes = [Constant::SMS_CODE_SCENE_LOGIN, Constant::SMS_CODE_SCENE_REGISTER];
        foreach ($scenes as $scene) {
            if ($sms->check($mobile, $request->get('code'), $scene)) {
                $pass = $scene;

                break;
            }
        }

        if ($pass !== false) {
            $user = $repository->mobile($mobile)->first();
            if ($user) {
                if ($pass === Constant::SMS_CODE_SCENE_REGISTER) {
                    $repository->update(['mobile_verified_at' => Carbon::now()->toDateTimeString()], $user->id);
                }
                $token = Auth::login($user);

                return $this->suc(compact('token'));
            }

            return $this->err(FeedBack::USER_NOT_FOUND);
        }

        return $this->err(FeedBack::SMS_CODE_INCORRECT);
    }

    /**
     * 用户登出
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return $this->suc(['message' => 'success'], 200);
    }

    /**
     * 用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::user();

        return $this->suc(compact('user'));
    }
}
