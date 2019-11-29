<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Goods.
 *
 * @package namespace App\Entities\Shop;
 */
class Goods extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'recommend_price',
        'price',
        'cost',
        'introduction',
        'details',
        'type',
        'verificate_type',
        'delivery_type',
        'pay_type',
        'status',
        'stock',
        'sold',
        'expire_start',
        'expire_end',
    ];

    /**
     * 不可批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 数组中的属性会被隐藏。
     *
     * @var array
     */
    protected $hidden  = [];

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'cost'            => 0,
        'type'            => 1,
        'verificate_type' => 1,
        'delivery_type'   => 1,
        'pay_type'        => 1,
        'status'          => 0,
        'stock'           => 1000000,
        'sold'            => 0,
        'expire_start'    => null,
        'expire_end'      => null,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];


}
