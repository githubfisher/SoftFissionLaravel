<?php
namespace App\Validators\Shop;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class GoodsValidator.
 *
 * @package namespace App\Validators\Shop;
 */
class GoodsValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'            => ['required', 'string'],
            'user_id'         => ['required', 'integer'],
            'shop_id'         => ['required', 'integer'],
            'recommend_price' => ['required', 'integer'],
            'price'           => ['required', 'integer'],
            'cost'            => ['sometimes', 'integer'],
            'introduction'    => ['sometimes', 'string'],
            'details'         => ['sometimes', 'string'],
            'type'            => ['required', 'integer'],
            'verificate_type' => ['required', 'integer'],
            'delivery_type'   => ['required', 'integer'],
            'pay_type'        => ['required', 'integer'],
            'status'          => ['sometimes', 'integer'],
            'stock'           => ['sometimes', 'integer'],
            'sold'            => ['sometimes', 'integer'],
            'expire_start'    => ['sometimes', 'date'],
            'expire_end'      => ['sometimes', 'date'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'            => ['sometimes', 'string'],
            'user_id'         => ['sometimes', 'integer'],
            'shop_id'         => ['sometimes', 'integer'],
            'recommend_price' => ['sometimes', 'integer'],
            'price'           => ['sometimes', 'integer'],
            'cost'            => ['sometimes', 'integer'],
            'introduction'    => ['sometimes', 'string'],
            'details'         => ['sometimes', 'string'],
            'type'            => ['sometimes', 'integer'],
            'verificate_type' => ['sometimes', 'integer'],
            'delivery_type'   => ['sometimes', 'integer'],
            'pay_type'        => ['sometimes', 'integer'],
            'status'          => ['sometimes', 'integer'],
            'stock'           => ['sometimes', 'integer'],
            'sold'            => ['sometimes', 'integer'],
            'expire_start'    => ['sometimes', 'date'],
            'expire_end'      => ['sometimes', 'date'],
        ],
    ];
}
