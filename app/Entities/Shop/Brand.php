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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Shop', 'shops_brands', 'brand_id', 'shop_id');
    }

}
