<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * 数组中的属性会被隐藏。
     *
     * @var array
     */
    protected $hidden  = [];

    /**
     * 不可批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 获取店铺
     *
     * @return BelongsToMany
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Shop', 'shops_brands', 'brand_id', 'shop_id');
    }
}
