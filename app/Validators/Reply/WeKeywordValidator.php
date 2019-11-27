<?php
namespace App\Validators\Reply;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WeKeywordValidator.
 *
 * @package namespace App\Validators\Reply;
 */
class WeKeywordValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'keyword'    => ['required', 'string', 'max:64'],
            'match_type' => ['required', 'integer', 'in:1,2'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'keyword'    => ['sometimes', 'required', 'string', 'max:64'],
            'match_type' => ['sometimes', 'required', 'integer', 'in:1,2'],
        ],
    ];
}
