<?php
namespace App\Http\Requests\User\Material;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
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
            'app_id'                       => 'required|string|min:18',
            'details'                      => 'required|array|min:1',
            'details.*.title'              => 'required|string|max:192',
            'details.*.thumb_url'          => 'required|string|max:255',
            'details.*.image_id'           => 'required|integer|max:1',
            'details.*.digest'             => 'sometimes|nullable|string|max:192',
            'details.*.author'             => 'sometimes|nullable|string|max:24',
            'details.*.content_source_url' => 'sometimes|nullable|string|max:255',
            'details.*.content'            => 'sometimes|nullable|string',
            'details.*.poster_id'          => 'sometimes|nullable|integer|min:1',
        ];
    }
}
