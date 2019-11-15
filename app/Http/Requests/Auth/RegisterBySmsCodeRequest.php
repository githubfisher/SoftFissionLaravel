<?php
namespace App\Http\Requests\Auth;

use App\Rules\CaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterBySmsCodeRequest extends FormRequest
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
            'key'     => 'required|string',
            'captcha' => ['required', new CaptchaRule],
            'mobile'  => 'required|mobile',
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
            'mobile.required'  => '手机号必须填写',
            'captcha.required' => '验证码必须填写',
        ];
    }
}
