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
        'user_id',
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
        'cost'   => 0,
        'status' => 0,
        'stock'  => 1000000,
        'sold'   => 0,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function getRecommendPriceAttribute($value)
    {
        return $value / 100;
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function getCostAttribute($value)
    {
        return $value / 100;
    }

    public function setRecommendPriceAttribute($value)
    {
        $this->attributes['recommend_price'] = $value * 100;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = $value * 100;
    }
}
