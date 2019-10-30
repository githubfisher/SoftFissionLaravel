<?php
namespace App\Http\Controllers\Api\Admin;

use Auth;
use Hash;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'mobile'           => 'mobile',
            'password'         => 'required|string|min:6|max:20',
        ]);

        $user = Admin::create([
            'mobile'   => $request->get('mobile'),
            'email'    => $request->get('email', ''),
            'name'     => $request->get('name', ''),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = Auth::login($user);

        return response()->json(compact('token'));
    }

    /**
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }
}
