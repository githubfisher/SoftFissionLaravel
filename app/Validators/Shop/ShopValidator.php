<?php

namespace App\Validators\Shop;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ShopValidator.
 *
 * @package namespace App\Validators\Shop;
 */
class ShopValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'type'         => ['required', 'integer', 'in:1,2'],
            'name'         => ['required', 'string', 'alpha_dash', 'max:64'],
            'mobile'       => ['required', 'mobile'],
            'introduction' => ['sometimes', 'string', 'alpha_dash'],
            'telephone'    => ['sometimes', 'required', 'string', 'alpha_dash'],
            'headimgurl'   => ['sometimes', 'required', 'string', 'alpha_dash'],
            'qrcode_url'   => ['sometimes', 'required', 'string', 'alpha_dash'],
            'wechat'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'weibo'        => ['sometimes', 'required', 'string', 'alpha_dash'],
            'douyin'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'location_x'   => ['sometimes', 'required', 'numeric'],
            'location_y'   => ['sometimes', 'required', 'numeric'],
            'country'      => ['sometimes', 'required', 'string', 'alpha_dash'],
            'province'     => ['sometimes', 'required', 'string', 'alpha_dash'],
            'city'         => ['sometimes', 'required', 'string', 'alpha_dash'],
            'county'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'address'      => ['sometimes', 'required', 'string', 'alpha_dash'],
            'start_at'     => ['sometimes', 'required', 'date_format:H:i'],
            'end_at'       => ['sometimes', 'required', 'date_format:H:i'],
            'details'      => ['sometimes', 'required', 'string'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'type'         => ['required', 'integer', 'in:1,2'],
            'name'         => ['required', 'string', 'alpha_dash', 'max:64'],
            'mobile'       => ['required', 'mobile'],
            'introduction' => ['sometimes', 'string', 'alpha_dash'],
            'telephone'    => ['sometimes', 'required', 'string', 'alpha_dash'],
            'headimgurl'   => ['sometimes', 'required', 'string', 'alpha_dash'],
            'qrcode_url'   => ['sometimes', 'required', 'string', 'alpha_dash'],
            'wechat'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'weibo'        => ['sometimes', 'required', 'string', 'alpha_dash'],
            'douyin'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'location_x'   => ['sometimes', 'required', 'numeric'],
            'location_y'   => ['sometimes', 'required', 'numeric'],
            'country'      => ['sometimes', 'required', 'string', 'alpha_dash'],
            'province'     => ['sometimes', 'required', 'string', 'alpha_dash'],
            'city'         => ['sometimes', 'required', 'string', 'alpha_dash'],
            'county'       => ['sometimes', 'required', 'string', 'alpha_dash'],
            'address'      => ['sometimes', 'required', 'string', 'alpha_dash'],
            'start_at'     => ['sometimes', 'required', 'date_format:H:i'],
            'end_at'       => ['sometimes', 'required', 'date_format:H:i'],
            'details'      => ['sometimes', 'required', 'string'],
        ],
    ];
}
