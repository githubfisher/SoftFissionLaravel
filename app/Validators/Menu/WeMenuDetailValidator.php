<?php
namespace App\Validators\Menu;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WeMenuDetailValidator.
 *
 * @package namespace App\Validators\Menu;
 */
class WeMenuDetailValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'    => ['required', 'string'],
            'menu_id' => ['required', 'integer', 'min:1'],
            'pid'     => ['sometimes', 'integer', 'min:0'],
            'rule_id' => ['sometimes', 'integer', 'min:0'],
            'status'  => ['sometimes', 'integer', 'in:0,1'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'    => ['sometimes', 'string'],
            'menu_id' => ['sometimes', 'integer', 'min:1'],
            'pid'     => ['sometimes', 'integer', 'min:0'],
            'rule_id' => ['sometimes', 'integer', 'min:0'],
            'status'  => ['sometimes', 'integer', 'in:0,1'],
        ],
    ];
}
