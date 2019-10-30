<?php

namespace App\Http\Requests\User;

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
            'mobile'   => 'sometimes|required|mobile|exists:user',
            'email'    => 'sometimes|required|email|exists:user',
            'code'     => 'required_without_all:email,password|string|min:4|max:4',
            'password' => 'required_without:code|string|min:6|max:20',
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
            'code.required'   => '验证码必须填写',
            'mobile.required' => '手机号必须填写',
            'mobile.exists'   => '手机号不存在',
            'email.exists'    => '邮箱不存在',
        ];
    }
}
