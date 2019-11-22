<?php
namespace App\Http\Requests\User\OpenPlatform\QrCode;

use App\Rules\AppIdRule;
use App\Rules\QrCode\ExpireInRule;
use App\Rules\QrCode\ExpireTypeRule;
use App\Rules\QrCode\TargetNumRule;
use App\Rules\QrCode\TypeRule;
use App\Rules\Reply\ContentRule;
use App\Rules\Reply\DifferenceRule;
use App\Rules\Reply\MaterialIdRule;
use App\Rules\Reply\RepliesRule;
use App\Rules\Reply\ReplyRuleRule;
use App\Rules\Reply\ReplyTypeRule;
use App\Rules\Reply\StartAtRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateWeQrcodeRequest extends FormRequest
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
            'app_id'                       => ['required', new AppIdRule],
            'title'                        => 'sometimes|required|string',
            'replies'                      => ['required', new RepliesRule],
            'replies.*.difference'         => ['required', new DifferenceRule],
            'replies.*.reply_type'         => ['sometimes', 'nullable', new ReplyTypeRule],
            'replies.*.reply_type_female'  => ['sometimes', 'nullable', new ReplyTypeRule],
            'replies.*.content'            => ['sometimes', 'nullable', new ContentRule],
            'replies.*.content_female'     => ['sometimes', 'nullable', new ContentRule],
            'replies.*.material_id'        => ['sometimes', 'nullable', new MaterialIdRule],
            'replies.*.material_id_female' => ['sometimes', 'nullable', new MaterialIdRule],
            'reply_rule'                   => ['required', new ReplyRuleRule],
            'start_at'                     => [new StartAtRule],
            'end_at'                       => [new StartAtRule],
            'target_num'                   => ['sometimes|required', new TargetNumRule],
            'type'                         => ['required', new TypeRule],
            'expire_type'                  => ['required_if:type,1', new ExpireTypeRule],
            'expire_in'                    => ['required_if:expire_type,1', new ExpireInRule],
            'expire_at'                    => 'required_if:expire_type,2|date',
        ];
    }
}
