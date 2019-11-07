<?php
namespace App\Http\Requests\User\AutoReply;

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
            'app_id'     => 'required|string|min:18',
            'title'      => 'required',
            'keywords'   => 'required|array',
            'replies'    => 'required|array',
            'reply_rule' => 'required|in:1,2',
            'start_at'   => 'nullable|date',
            'end_at'     => 'nullable|date',
        ];
    }
}
