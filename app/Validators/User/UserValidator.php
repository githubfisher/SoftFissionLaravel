<?php
namespace App\Validators\User;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class UserValidator.
 *
 * @package namespace App\Validators\User;
 */
class UserValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'email'      => ['sometimes', 'required', 'email'],
            'mobile'     => ['sometimes', 'required', 'mobile'],
            'openid'     => ['sometimes', 'required', 'string', 'alpha_dash'],
            'nickname'   => ['sometimes', 'required', 'string'],
            'headimgurl' => ['sometimes', 'nullable', 'required', 'url'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'email'              => ['sometimes', 'required', 'email'],
            'mobile'             => ['sometimes', 'required', 'mobile'],
            'mobile_verified_at' => ['sometimes', 'required', 'datetime'],
            'email_verified_at'  => ['sometimes', 'required', 'datetime'],
            'password'           => ['sometimes', 'required', 'string'],
            'name'               => ['sometimes', 'required', 'string', 'max:64', 'alpha_dash'],
            'openid'             => ['sometimes', 'required', 'string', 'alpha_dash'],
            'nickname'           => ['sometimes', 'required', 'string'],
            'headimgurl'         => ['sometimes', 'nullable', 'required', 'url'],
        ],
    ];
}
