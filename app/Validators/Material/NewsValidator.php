<?php
namespace App\Validators\Material;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class NewsValidatorValidator.
 *
 * @package namespace App\Validators\Material;
 */
class NewsValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'user_id'  => 'required|integer|min:1',
            'app_id'   => 'required|string|min:18',
            'media_id' => 'sometimes|required|string',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'user_id'  => 'sometimes|required|integer|min:1',
            'app_id'   => 'sometimes|required|string|min:18',
            'media_id' => 'sometimes|required|string',
        ],
    ];
}
