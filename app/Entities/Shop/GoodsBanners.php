<?php
namespace App\Entities\Shop;

use App\Entities\Model;

/**
 * Class Shop.
 *
 * @package namespace App\Entities\Shop;
 */
class GoodsBanners extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'goods_id',
        'banner_id',
        'banner_type',
        'sort',
    ];
}
