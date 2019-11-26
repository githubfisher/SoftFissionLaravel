<?php
namespace App\Validators\Menu;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class MenuValidator.
 *
 * @package namespace App\Validators\Menu;
 */
class MenuValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'menus'                                     => ['required', 'array', 'min:1'],
            'menus.*.type'                              => ['required', 'integer', 'in:1,2'],
            'menus.*.filter'                            => ['nullable', 'array'],
            'menus.*.buttons'                           => ['required', 'array', 'min:1'],
            'menus.*.buttons.*.name'                    => ['required', 'string'],
            'menus.*.buttons.*.type'                    => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.difference'              => ['required', 'integer', 'in:1,2'],
            'menus.*.buttons.*.reply_type'              => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.reply_type_female'       => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.content'                 => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.content_female'          => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.material_id'             => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.material_id_female'      => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.url'                     => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.url_female'              => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.mini_app_id'             => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.mini_app_id_female'      => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs'                    => ['required', 'array'],
            'menus.*.buttons.*.subs.difference'         => ['required', 'integer', 'in:1,2'],
            'menus.*.buttons.*.subs.reply_type'         => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.subs.reply_type_female'  => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.subs.content'            => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.content_female'     => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.material_id'        => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.subs.material_id_female' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.subs.url'                => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.subs.url_female'         => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.subs.mini_app_id'        => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs.mini_app_id_female' => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'menus'                                     => ['required', 'array', 'min:1'],
            'menus.*.type'                              => ['required', 'integer', 'in:1,2'],
            'menus.*.filter'                            => ['nullable', 'array'],
            'menus.*.buttons'                           => ['required', 'array', 'min:1'],
            'menus.*.buttons.*.name'                    => ['required', 'string'],
            'menus.*.buttons.*.type'                    => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.difference'              => ['required', 'integer', 'in:1,2'],
            'menus.*.buttons.*.reply_type'              => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.reply_type_female'       => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.content'                 => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.content_female'          => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.material_id'             => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.material_id_female'      => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.url'                     => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.url_female'              => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.mini_app_id'             => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.mini_app_id_female'      => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs'                    => ['required', 'array'],
            'menus.*.buttons.*.subs.difference'         => ['required', 'integer', 'in:1,2'],
            'menus.*.buttons.*.subs.reply_type'         => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.subs.reply_type_female'  => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'menus.*.buttons.*.subs.content'            => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.content_female'     => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.material_id'        => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.subs.material_id_female' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'menus.*.buttons.*.subs.url'                => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.subs.url_female'         => ['sometimes', 'nullable', 'string'],
            'menus.*.buttons.*.subs.mini_app_id'        => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs.mini_app_id_female' => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
        ],
    ];
}
