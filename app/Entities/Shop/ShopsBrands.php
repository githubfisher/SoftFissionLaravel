<?php
namespace App\Entities\Shop;

use App\Entities\Model;

/**
 * Class Shop.
 *
 * @package namespace App\Entities\Shop;
 */
class ShopsBrands extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'brand_id',
    ];

    /**
     * 不可批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];
}
