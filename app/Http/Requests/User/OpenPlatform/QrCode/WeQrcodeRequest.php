<?php
namespace App\Http\Requests\User\OpenPlatform\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class WeQrcodeRequest extends FormRequest
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
            'limit' => 'sometimes|required|integer|min:10',
        ];
    }
}
