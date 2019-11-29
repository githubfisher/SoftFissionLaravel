<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Promotion.
 *
 * @package namespace App\Entities\Shop;
 */
class Promotion extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'group_id',
        'sort',
        'min_count',
        'min_sum',
        'sub_sum',
        'plus_point',
        'limit',
        'expires',
        'members',
        'products',
        'expire_start',
        'expire_end',
        'introduction',
        'exclusive',
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
        'group_id'   => 1,
        'sort'       => 0,
        'min_count'  => 1,
        'min_sum'    => 1,
        'sub_sum'    => 0,
        'plus_point' => 0,
        'limit'      => 0,
        'expires'    => 0,
        'members'    => '',
        'products'   => '',
        'exclusive'  => 1,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'exclusive' => 'boolean',
    ];

    /**
     * 获取关联商品
     *
     * @return BelongsToMany
     */
    public function goods(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Goods', 'goods_promotions', 'promotion_id', 'goods_id');
    }

    public function scopeShop(Builder $query, $shops)
    {
        return $query->whereIn('shop_id', $shops);
    }
}
