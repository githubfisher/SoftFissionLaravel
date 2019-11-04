<?php
namespace App\Http\Controllers\Api\User;

use Auth;
use Hash;
use App\Models\User\User;
use App\Http\Repositories\Sms;
use App\Http\Utilities\Constant;
use App\Http\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetNameRequest;
use App\Http\Requests\User\ResetMobileRequest;
use App\Http\Requests\User\ResetPasswordRequest;

class UserController extends Controller
{
    /**
     * 修改用户名
     *
     * @param ResetNameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetName(ResetNameRequest $request)
    {
        $user       = User::find($this->user()->id);
        $user->name = $request->input('name');
        if ($user->update()) {
            return $this->suc();
        }

        return $this->err(FeedBack::USERNAME_RESET_FAIL);
    }

    /**
     * 修改手机号
     *
     * @param ResetMobileRequest $request
     * @param Sms                $sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetMobile(ResetMobileRequest $request, Sms $sms)
    {
        $mobile = $request->get('mobile');
        if ($sms->check($mobile, $request->get('code'), Constant::SMS_CODE_SCENE_RESET)) {
            $user         = User::find($this->user()->id);
            $user->mobile = $mobile;
            if ($user->update()) {
                return $this->suc();
            }

            return $this->err(FeedBack::MOBILE_RESET_FAIL);
        }

        return $this->err(FeedBack::SMS_CODE_INCORRECT);
    }

    /**
     * 修改密码
     *
     * @param ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        if ($token = Auth::attempt(['mobile' => $this->user()->mobile, 'password' => $request->input('password')])) {
            $user           = User::find($this->user()->id);
            $user->password = Hash::make($request->get('new_password'));
            if ($user->update()) {
                Auth::logout();

                return $this->suc();
            }

            return $this->err(FeedBack::PASSWORD_RESET_FAIL);
        }

        return $this->err(FeedBack::PASSWORD_INCORRECT);
    }
}
