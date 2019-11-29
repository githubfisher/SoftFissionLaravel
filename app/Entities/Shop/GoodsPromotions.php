<?php
namespace App\Entities\Shop;

use App\Entities\Model;

/**
 * Class Shop.
 *
 * @package namespace App\Entities\Shop;
 */
class GoodsPromotions extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'goods_id',
        'promotions_id',
    ];
}
