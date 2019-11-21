<?php
namespace App\Http\Requests\User\OpenPlatform\AutoReply;

use Illuminate\Foundation\Http\FormRequest;

class CreateRuleRequest extends FormRequest
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
            'title'                        => 'sometimes|required|string',
            'keywords'                     => 'sometimes|required|array',
            'keywords.*.keyword'           => 'required_with:keywords|string|min:1|max:64',
            'keywords.*.match_type'        => 'required_with:keywords|integer|in:1,2',
            'replies'                      => 'required|array|min:1',
            'replies.*.difference'         => 'required|integer|in:1,2',
            'replies.*.reply_type'         => 'sometimes|nullable|integer|in:1,2,3,4,5,6',
            'replies.*.reply_type_female'  => 'sometimes|nullable|integer|in:1,2,3,4,5,6',
            'replies.*.content'            => 'sometimes|nullable|string|max:2048',
            'replies.*.content_female'     => 'sometimes|nullable|string|max:2048',
            'replies.*.material_id'        => 'sometimes|nullable|integer|min:1',
            'replies.*.material_id_female' => 'sometimes|nullable|integer|min:1',
            'reply_rule'                   => 'required|in:1,2',
            'start_at'                     => 'nullable|date',
            'end_at'                       => 'nullable|date',
        ];
    }
}
