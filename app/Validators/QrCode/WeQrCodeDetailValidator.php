<?php
namespace App\Validators\QRCode;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WeQrCodeDetailValidator.
 *
 * @package namespace App\Validators\QRCode;
 */
class WeQrCodeDetailValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'qrcode_id'     => ['required', 'integer', 'min:1'],
            'batch'         => ['required', 'string'],
            'expire_at'     => ['nullable', 'date'],
            'scan_num'      => ['sometimes', 'integer'],
            'subscribe_num' => ['sometimes', 'integer'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'url'           => ['sometimes', 'required', 'url'],
            'ticket'        => ['sometimes', 'required', 'string'],
            'scene_str'     => ['sometimes', 'required', 'string'],
            'scan_num'      => ['sometimes', 'required', 'integer'],
            'subscribe_num' => ['sometimes', 'required', 'integer'],
        ],
    ];
}
