<?php
namespace App\Http\Requests\User\Auth;

use App\Rules\NumCodeRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|unique:user',
            'code'     => ['required', new NumCodeRule],
            'password' => ['required', new PasswordRule],
        ];
    }

    /**
     * 获取验证错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'    => '邮箱必须填写',
            'email.unique'      => '邮箱已注册',
            'code.required'     => '验证码必须填写',
            'password.required' => '密码必须填写',
        ];
    }
}
