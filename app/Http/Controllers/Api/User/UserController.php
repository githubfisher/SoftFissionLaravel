<?php
namespace App\Http\Controllers\Api\User;

use Auth;
use Hash;
use App\Services\Sms;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ResetNameRequest;
use App\Repositories\User\UserRepositoryEloquent;
use App\Http\Requests\User\Auth\ResetMobileRequest;
use App\Http\Requests\User\Auth\ResetPasswordRequest;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 修改用户名
     *
     * @param ResetNameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function resetName(ResetNameRequest $request)
    {
        if ($res = $this->repository->update(['name' => $request->input('name')], $this->user()->id)) {
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
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function resetMobile(ResetMobileRequest $request, Sms $sms)
    {
        $mobile = $request->get('mobile');
        if ($sms->check($mobile, $request->get('code'), Constant::SMS_CODE_SCENE_RESET)) {
            if ($res = $this->repository->update(['mobile' => $mobile], $this->user()->id)) {
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
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        if ($token = Auth::attempt(['mobile' => $this->user()->mobile, 'password' => $request->input('password')])) {
            $password = Hash::make($request->get('new_password'));
            if ($res = $this->repository->update(['password' => $password], $this->user()->id)) {
                Auth::logout();

                return $this->suc();
            }

            return $this->err(FeedBack::PASSWORD_RESET_FAIL);
        }

        return $this->err(FeedBack::PASSWORD_INCORRECT);
    }
}
