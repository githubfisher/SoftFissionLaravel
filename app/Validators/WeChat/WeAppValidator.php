<?php
namespace App\Validators\WeChat;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class AppValidator.
 *
 * @package namespace App\Validators\WeChat;
 */
class WeAppValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'user_id'            => ['required', 'integer'],
            'app_id'             => ['required', 'string', 'min:18', 'alpha_dash'],
            'refresh_token'      => ['required', 'string'],
            'nick_name'          => ['required', 'string'],
            'head_img'           => ['required', 'url'],
            'user_name'          => ['required', 'string'],
            'alias'              => ['nullable', 'string'],
            'qrcode_url'         => ['required', 'url'],
            'principal_name'     => ['required', 'string'],
            'signature'          => ['nullable', 'string'],
            'service_type_info'  => ['required', 'integer'],
            'verify_type_info'   => ['required', 'integer'],
            'deleted_at'         => ['nullable', 'datetime'],
            'funcscope_category' => ['nullable', 'json'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'app_id'             => ['required', 'string', 'min:18', 'alpha_dash'],
            'refresh_token'      => ['required', 'string'],
            'nick_name'          => ['required', 'string'],
            'head_img'           => ['required', 'url'],
            'user_name'          => ['required', 'string'],
            'alias'              => ['nullable', 'string'],
            'qrcode_url'         => ['required', 'url'],
            'principal_name'     => ['required', 'string'],
            'signature'          => ['nullable', 'string'],
            'service_type_info'  => ['required', 'integer'],
            'verify_type_info'   => ['required', 'integer'],
            'deleted_at'         => ['nullable', 'datetime'],
            'funcscope_category' => ['nullable', 'json'],
        ],
    ];
}
