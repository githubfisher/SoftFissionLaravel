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
            'app_id' => ['required', 'string', 'min:18', 'alpha_dash'],
            'type'   => ['required', 'integer', 'in:1,2'],
            'filter' => ['nullable', 'array'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'app_id' => ['sometimes', 'string', 'min:18', 'alpha_dash'],
            'type'   => ['sometimes', 'integer', 'in:1,2'],
            'filter' => ['nullable', 'array'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ],
    ];
}
