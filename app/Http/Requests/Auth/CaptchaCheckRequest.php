<?php
namespace App\Http\Requests\Auth;

use App\Rules\CaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class CaptchaCheckRequest extends FormRequest
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
            'mobile'  => 'required|string|mobile',
            'scene'   => 'required|string|in:register,login,reset,auth',
        ];
    }
}
