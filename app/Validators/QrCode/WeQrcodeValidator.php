<?php
namespace App\Validators\QrCode;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class QrCodeValidator.
 *
 * @package namespace App\Validators\QrCode;
 */
class WeQrcodeValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'app_id'      => ['required', 'string', 'min:18', 'alpha_dash'],
            'rule_id'     => ['required', 'integer', 'min:1'],
            'title'       => ['required', 'string', 'alpha_dash'],
            'type'        => ['required', 'integer', 'in:1,2'],
            'target_num'  => ['required', 'integer', 'min:1'],
            'expire_type' => ['required_if:type,1', 'integer', 'in:1,2'],
            'expire_at'   => ['required_if:expire_type,1', 'integer', 'min:1'],
            'expire_in'   => ['required_if:expire_type,2', 'date'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'app_id'      => ['sometimes', 'required', 'string', 'min:18', 'alpha_dash'],
            'rule_id'     => ['sometimes', 'required', 'integer', 'min:1'],
            'title'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'type'        => ['sometimes', 'required', 'integer', 'in:1,2'],
            'target_num'  => ['sometimes', 'required', 'integer', 'min:1'],
            'expire_type' => ['required_if:type,1', 'integer', 'in:1,2'],
            'expire_at'   => ['required_if:expire_type,1', 'integer', 'min:1'],
            'expire_in'   => ['required_if:expire_type,2', 'date'],
        ],
    ];
}
