<?php
namespace App\Http\Controllers\Api\User;

use Auth;
use Hash;
use App\Models\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use Dingo\Blueprint\Annotation\Response;
use Dingo\Blueprint\Annotation\Method\Get;
use App\Http\Requests\User\RegisterRequest;
use Dingo\Blueprint\Annotation\Method\Post;

class AuthController extends Controller
{
    /**
     * 用户注册
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'mobile'   => $request->get('mobile'),
            'email'    => $request->get('email', ''),
            'name'     => $request->get('name', ''),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = Auth::login($user);

        return response()->json(compact('token'));
    }

    /**
     * 用户登录
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['mobile', 'password']);

        return (($token = Auth::attempt($credentials))
            ? response()->json(['token' => $token], 201)
            : response()->json(['error' => '账号或密码错误'], 401));
    }

    /**
     * 用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * 用户登出
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'success'], 200);
    }
}
