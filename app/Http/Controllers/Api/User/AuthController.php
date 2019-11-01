<?php
namespace App\Http\Controllers\Api\User;

use Auth;
use Hash;
use App\Models\User\User;
use App\Http\Repositories\Sms;
use App\Http\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginBySmsCodeRequest;

class AuthController extends Controller
{
    /**
     * 用户注册
     * @param RegisterRequest $request
     * @param Sms             $sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request, Sms $sms)
    {
        $mobile = $request->get('mobile');
        if ($sms->check($mobile, $request->get('code'))) {
            $user = User::create([
                'mobile'   => $request->get('mobile'),
                'email'    => $request->get('email', ''),
                'name'     => $request->get('name', ''),
                'password' => Hash::make($request->get('password')),
            ]);
            $token = Auth::login($user);

            return $this->suc(compact('token'));
        }

        return $this->err(FeedBack::SMS_CODE_INCORRECT);
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
     * 登录: 手机号+验证码
     * @param LoginBySmsCodeRequest $request
     * @param Sms                   $sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginBySmsCode(LoginBySmsCodeRequest $request, Sms $sms)
    {
        $mobile = $request->get('mobile');
        if ($sms->check($mobile, $request->get('code'))) {
            $user  = User::where('mobile', $mobile)->find(1);
            $token = Auth::login($user);

            return $this->suc(compact('token'));
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
        return $this->suc(Auth::user());
    }
}
