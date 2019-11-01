<?php
namespace App\Http\Controllers\Api\Admin;

use Auth;
use Hash;
use App\Models\Admin\Admin;
use App\Http\Repositories\Sms;
use App\Http\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\RegisterRequest;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param Sms             $sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request, Sms $sms)
    {
        $mobile = $request->get('mobile');
        if ($sms->check($mobile, $request->get('code'))) {
            $user  = Admin::create([
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
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['mobile', 'password']);

        return (($token = Auth::attempt($credentials))
            ? $this->suc(['token' => $token], 201)
            : $this->err(['error' => '账号或密码错误'], 401));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->suc(Auth::user());
    }
}
