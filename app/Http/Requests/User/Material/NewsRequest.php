<?php
namespace App\Http\Requests\User\Material;

use App\Rules\LimitRule;
use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'app_id' => 'required|string|min:18',
            'limit'  => ['sometimes', 'required', new LimitRule],
        ];
    }
}
