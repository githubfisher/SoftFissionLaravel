<?php
namespace App\Http\Requests\User\OpenPlatform\AutoReply;

use App\Rules\AppIdRule;
use App\Rules\Reply\ContentRule;
use App\Rules\Reply\KeywordRule;
use App\Rules\Reply\RepliesRule;
use App\Rules\Reply\StartAtRule;
use App\Rules\Reply\KeywordsRule;
use App\Rules\Reply\MatchTypeRule;
use App\Rules\Reply\ReplyRuleRule;
use App\Rules\Reply\ReplyTypeRule;
use App\Rules\Reply\DifferenceRule;
use App\Rules\Reply\MaterialIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateWeRuleRequest extends FormRequest
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
            'keywords'                     => ['sometimes|required', new KeywordsRule],
            'keywords.*.keyword'           => ['required_with:keywords', new KeywordRule],
            'keywords.*.match_type'        => ['required_with:keywords', new MatchTypeRule],
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
        ];
    }
}
