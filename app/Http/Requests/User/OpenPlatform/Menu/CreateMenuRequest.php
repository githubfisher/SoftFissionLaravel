<?php
namespace App\Http\Requests\User\OpenPlatform\Menu;

use App\Rules\AppIdRule;
use App\Rules\Reply\ContentRule;
use App\Rules\Reply\ReplyTypeRule;
use App\Rules\Reply\DifferenceRule;
use App\Rules\Reply\MaterialIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateMenuRequest extends FormRequest
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
            'menus'                                     => ['required', 'array', 'min:1'],
            'menus.*.type'                              => ['required', 'integer', 'in:1,2'],
            'menus.*.filter'                            => ['nullable', 'array'],
            'menus.*.buttons'                           => ['required', 'array', 'min:1'],
            'menus.*.buttons.*.name'                    => ['required', 'string'],
            'menus.*.buttons.*.type'                    => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.difference'              => ['required', new DifferenceRule],
            'menus.*.buttons.*.reply_type'              => ['sometimes', 'nullable', new ReplyTypeRule],
            'menus.*.buttons.*.reply_type_female'       => ['sometimes', 'nullable', new ReplyTypeRule],
            'menus.*.buttons.*.content'                 => ['sometimes', 'nullable', new ContentRule],
            'menus.*.buttons.*.content_female'          => ['sometimes', 'nullable', new ContentRule],
            'menus.*.buttons.*.material_id'             => ['sometimes', 'nullable', new MaterialIdRule],
            'menus.*.buttons.*.material_id_female'      => ['sometimes', 'nullable', new MaterialIdRule],
            'menus.*.buttons.*.url'                     => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.url_female'              => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.mini_app_id'             => ['sometimes', 'nullable', new AppIdRule],
            'menus.*.buttons.*.mini_app_id_female'      => ['sometimes', 'nullable', new AppIdRule],
            'menus.*.buttons.*.subs'                    => ['required', 'array'],
            'menus.*.buttons.*.subs.difference'         => ['required', new DifferenceRule],
            'menus.*.buttons.*.subs.reply_type'         => ['sometimes', 'nullable', new ReplyTypeRule],
            'menus.*.buttons.*.subs.reply_type_female'  => ['sometimes', 'nullable', new ReplyTypeRule],
            'menus.*.buttons.*.subs.content'            => ['sometimes', 'nullable', new ContentRule],
            'menus.*.buttons.*.subs.content_female'     => ['sometimes', 'nullable', new ContentRule],
            'menus.*.buttons.*.subs.material_id'        => ['sometimes', 'nullable', new MaterialIdRule],
            'menus.*.buttons.*.subs.material_id_female' => ['sometimes', 'nullable', new MaterialIdRule],
            'menus.*.buttons.*.subs.url'                => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.subs.url_female'         => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.subs.mini_app_id'        => ['sometimes', 'nullable', new AppIdRule],
            'menus.*.buttons.*.subs.mini_app_id_female' => ['sometimes', 'nullable', new AppIdRule],
        ];
    }
}
