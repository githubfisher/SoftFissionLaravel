<?php
namespace App\Http\Requests\User\Auth;

use App\Rules\CaptchaRule;
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
            'email'   => 'required|email|unique:user',
            'key'     => 'required|string',
            'captcha' => ['required', new CaptchaRule],
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
            'email.required'   => '邮箱必须填写',
            'email.unique'     => '邮箱已注册',
            'captcha.required' => '验证码必须填写',
        ];
    }
}
