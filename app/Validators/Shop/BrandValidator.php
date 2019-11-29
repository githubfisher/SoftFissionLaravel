<?php

namespace App\Validators\Shop;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class BrandValidator.
 *
 * @package namespace App\Validators\Shop;
 */
class BrandValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => ['required', 'string', 'alpha_dash', 'max:64'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => ['required', 'string', 'alpha_dash', 'max:64'],
        ],
    ];
}
