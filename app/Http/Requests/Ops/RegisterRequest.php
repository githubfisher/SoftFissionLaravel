<?php

namespace App\Http\Requests\Ops;

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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'     => 'required|string|min:4|max:4',
            'mobile'   => 'required|mobile|unique:ops',
            'password' => 'required|string|min:6|max:20',
            'name'     => 'sometimes|required|string|max:64',
            'email'    => 'sometimes|required|email|unique:ops',
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
            'mobile.unique'   => '手机号已注册',
            'email.unique'    => '邮箱已注册',
        ];
    }
}
