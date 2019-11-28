<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Brand.
 *
 * @package namespace App\Entities\Shop;
 */
class Brand extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'name',
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

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Shop', 'shops_brands', 'project_id', 'shop_id');
    }
}
