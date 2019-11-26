<?php
namespace App\Http\Requests\User\OpenPlatform\Menu;

use App\Rules\AppIdRule;
use App\Rules\Reply\ContentRule;
use App\Rules\Reply\DifferenceRule;
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
            'menus'                                   => ['required', 'array', 'min:1'],
            'menus.*.type'                            => ['required', 'integer', 'in:1,2'],
            'menus.*.filter'                          => ['required_if:menus.*.type,2', 'array'],
            'menus.*.buttons'                         => ['required', 'array', 'min:1'],
            'menus.*.buttons.*.name'                  => ['required', 'string'],
            'menus.*.buttons.*.type'                  => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.difference'            => ['required', new DifferenceRule],
            'menus.*.buttons.*.content'               => ['required', new ContentRule],
            'menus.*.buttons.*.content_female'        => ['required_if:menus.*.buttons.*.difference,1', new ContentRule],
            'menus.*.buttons.*.mini_appid'            => ['required_if:menus.*.buttons.*.type,8', new AppIdRule],
            'menus.*.buttons.*.pagepath'              => ['required_if:menus.*.buttons.*.type,8', 'string'],
            'menus.*.buttons.*.subs'                  => ['sometimes', 'array'],
            'menus.*.buttons.*.subs.*.name'           => ['sometimes', 'string'],
            'menus.*.buttons.*.subs.*.type'           => ['sometimes', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.subs.*.difference'     => ['sometimes', new DifferenceRule],
            'menus.*.buttons.*.subs.*.content'        => ['sometimes', new ContentRule],
            'menus.*.buttons.*.subs.*.content_female' => ['required_if:menus.*.buttons.*.subs.*.difference,1', new ContentRule],
            'menus.*.buttons.*.subs.*.mini_appid'     => ['required_if:menus.*.buttons.*.subs.*.type,8', new AppIdRule],
            'menus.*.buttons.*.subs.*.pagepath'       => ['required_if:menus.*.buttons.*.subs.*.type,8', 'string'],
        ];
    }
}
