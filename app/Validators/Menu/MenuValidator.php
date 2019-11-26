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
            'menus'                                      => ['required', 'array', 'min:1'],
            'menus.*.type'                               => ['required', 'integer', 'in:1,2'],
            'menus.*.filter'                             => ['nullable', 'array'],
            'menus.*.buttons'                            => ['required', 'array', 'min:1'],
            'menus.*.buttons.*.name'                     => ['required', 'string'],
            'menus.*.buttons.*.type'                     => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.difference'               => ['required', 'integer', 'in:0,1'],
            'menus.*.buttons.*.content'                  => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.content_female'           => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.mini_appid'               => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.pagepath'                 => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs'                     => ['sometimes', 'array'],
            'menus.*.buttons.*.subs.*.type'              => ['sometimes', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.subs.*.difference'        => ['sometimes', 'integer', 'in:0,1'],
            'menus.*.buttons.*.subs.*.content'           => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.*.content_female'    => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.*.mini_app_id'       => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs.*.pagepath'          => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'menus'                                      => ['required', 'array', 'min:1'],
            'menus.*.type'                               => ['required', 'integer', 'in:1,2'],
            'menus.*.filter'                             => ['nullable', 'array'],
            'menus.*.buttons'                            => ['required', 'array', 'min:1'],
            'menus.*.buttons.*.name'                     => ['required', 'string'],
            'menus.*.buttons.*.type'                     => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.difference'               => ['required', 'integer', 'in:0,1'],
            'menus.*.buttons.*.content'                  => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.content_female'           => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.mini_appid'               => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.pagepath'                 => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs'                     => ['sometimes', 'array'],
            'menus.*.buttons.*.subs.*.type'              => ['sometimes', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13'],
            'menus.*.buttons.*.subs.*.difference'        => ['sometimes', 'integer', 'in:0,1'],
            'menus.*.buttons.*.subs.*.content'           => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.*.content_female'    => ['sometimes', 'nullable', 'string', 'max:2048'],
            'menus.*.buttons.*.subs.*.mini_app_id'       => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
            'menus.*.buttons.*.subs.*.pagepath'          => ['sometimes', 'nullable', 'string', 'min:18', 'alpha_dash'],
        ],
    ];
}
