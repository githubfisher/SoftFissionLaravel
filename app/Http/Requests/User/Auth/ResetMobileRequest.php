<?php
namespace App\Http\Requests\User\Auth;

use App\Rules\NumCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetMobileRequest extends FormRequest
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
            'mobile' => 'required|mobile|unique:user',
            'code'   => ['required', new NumCodeRule],
        ];
    }
}
