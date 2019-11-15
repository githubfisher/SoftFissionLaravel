<?php
namespace App\Http\Requests\User\Auth;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password' => ['required', new PasswordRule],
            'mobile'   => 'sometimes|required|mobile|exists:user',
            'email'    => 'sometimes|required|email|exists:user',
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
            'mobile.required'   => '手机号必须填写',
            'password.required' => '密码必须填写',
            'mobile.exists'     => '手机号不存在',
            'email.exists'      => '邮箱不存在',
        ];
    }
}
