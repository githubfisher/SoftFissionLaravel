<?php
namespace App\Validators\Reply;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class RuleValidator.
 *
 * @package namespace App\Validators\Reply;
 */
class WeRuleValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'app_id'     => ['required', 'string', 'min:18', 'alpha_dash'],
            'scene'      => ['required', 'string'],
            'title'      => ['required', 'string'],
            'reply_rule' => ['required', 'integer', 'in:1,2'],
            'start_at'   => ['nullable', 'datetime'],
            'end_at'     => ['nullable', 'datetime'],
            'status'     => ['required', 'integer', 'in:0,1'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title'      => ['sometimes', 'required', 'string'],
            'reply_rule' => ['sometimes', 'required', 'integer', 'in:1,2'],
            'start_at'   => ['sometimes', 'nullable', 'datetime'],
            'end_at'     => ['sometimes', 'nullable', 'datetime'],
            'status'     => ['sometimes', 'required', 'integer', 'in:0,1'],
        ],
    ];
}
