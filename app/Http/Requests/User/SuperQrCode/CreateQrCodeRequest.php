<?php
namespace App\Http\Requests\User\SuperQrCode;

use Illuminate\Foundation\Http\FormRequest;

class CreateQrCodeRequest extends FormRequest
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
            'target_num'                   => 'sometimes|required|integer|min:1',
            'type'                         => 'required|integer|in:1,2',
            'expire_type'                  => 'required_if:type,1|integer|in:1,2',
            'expire_in'                    => 'required_if:expire_type,1|integer|min:1',
            'expire_at'                    => 'required_if:expire_type,2|date',
        ];
    }
}
