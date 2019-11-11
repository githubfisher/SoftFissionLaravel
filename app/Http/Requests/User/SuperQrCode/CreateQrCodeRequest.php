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
            'app_id'      => 'required|string|min:18',
            'title'       => 'sometimes|required|string',
            'keywords'    => 'sometimes|required|array',
            'replies'     => 'required|array',
            'reply_rule'  => 'required|in:1,2',
            'start_at'    => 'nullable|date',
            'end_at'      => 'nullable|date',
            'target_num'  => 'sometimes|required|integer|min:1',
            'type'        => 'required|integer|in:1,2',
            'expire_type' => 'required_if:type,1|integer|in:1,2',
            'expire_in'   => 'required_if:expire_type,1|required|integer|min:1',
            'expire_at'   => 'required_if:expire_type,2|required|date',
        ];
    }
}
