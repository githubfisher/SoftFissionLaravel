<?php
namespace App\Http\Controllers\Api\User;

use Auth;
use Hash;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Blueprint\Annotation\Response;
use Dingo\Blueprint\Annotation\Method\Get;
use Dingo\Blueprint\Annotation\Method\Post;

/**
 * 用户认证
 *
 * @Resource("User\Auth", uri="/user/auth")
 */
class AuthController extends Controller
{
    /**
     * 用户注册
     * @Post("/register")
     * @Versions({"v1"})
     * @Transaction({
     *  @request({"mobile": "13312348765", "password": "123456", "username": "foo", "email": "123@456.com"}),
     *  @Response(200, body={"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zZlwvdXNlclwvbWUiLCJpYXQiOjE1NzE5NzI5NTksImV4cCI6MTU3MTk3MzA4OCwibmJmIjoxNTcxOTczMDI4LCJqdGkiOiJwY2R2cktDbzg5VzFCSXJGIiwic3ViIjoxfQ.29Zsj7M7Eng6H27K7Hg_lwtgxqdk__EA03r0rg3ythQ"}),
     * })
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'mobile'           => 'mobile',
            'password'         => 'required|min:6|max:20',
        ]);

        $user = User::create([
            'mobile'   => $request->get('mobile'),
            'email'    => $request->get('email'),
            'name'     => $request->get('name'),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = Auth::login($user);

        return response()->json(compact('token'));
    }

    /**
     * 用户登录
     * @Post("/login")
     * @Versions({"v1"})
     * @Transaction({
     *  @request({"mobile": "13312348765", "password": "123456"}),
     *  @Response(201, body={"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zZlwvdXNlclwvbWUiLCJpYXQiOjE1NzE5NzI5NTksImV4cCI6MTU3MTk3MzA4OCwibmJmIjoxNTcxOTczMDI4LCJqdGkiOiJwY2R2cktDbzg5VzFCSXJGIiwic3ViIjoxfQ.29Zsj7M7Eng6H27K7Hg_lwtgxqdk__EA03r0rg3ythQ"}),
     *  @Response(401, body={"message": "账号或密码错误"})
     * })
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile'   => 'required|mobile',
            'password' => 'required|string|min:6|max:20',
        ]);

        $credentials = $request->only(['mobile', 'password']);

        return (($token = Auth::attempt($credentials))
            ? response()->json(['token' => $token], 201)
            : response()->json(['error' => '账号或密码错误'], 401));
    }

    /**
     * 用户信息
     * @Get("/me")
     * @Versions({"v1"})
     * @Transaction({
     *  @request(headers={"Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zZlwvdXNlclwvbWUiLCJpYXQiOjE1NzE5NzI5NTksImV4cCI6MTU3MTk3MzA4OCwibmJmIjoxNTcxOTczMDI4LCJqdGkiOiJwY2R2cktDbzg5VzFCSXJGIiwic3ViIjoxfQ.29Zsj7M7Eng6H27K7Hg_lwtgxqdk__EA03r0rg3ythQ"}),
     *  @Response(200, body={"id":1,"pid":0,"username":"foo","mobile":"13312348765","email":"123@456.com","openid":"","nickname":"","headimgurl":"","deleted_at":null,"created_at":"2019-10-24 16:45:09","updated_at":"2019-10-24 16:45:09"}),
     *  @Response(401, body={"message": "Unauthorized"})
     * })
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * 用户登出
     * @Get("/logout")
     * @Versions({"v1"})
     * @Transaction({
     *  @request(headers={"Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zZlwvdXNlclwvbWUiLCJpYXQiOjE1NzE5NzI5NTksImV4cCI6MTU3MTk3MzA4OCwibmJmIjoxNTcxOTczMDI4LCJqdGkiOiJwY2R2cktDbzg5VzFCSXJGIiwic3ViIjoxfQ.29Zsj7M7Eng6H27K7Hg_lwtgxqdk__EA03r0rg3ythQ"}),
     *  @Response(200, body={}),
     *  @Response(401, body={"message": "Unauthorized"}),
     *  @Response(401, body={"message": "Token has expired"}),
     * })
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'success'], 200);
    }
}
