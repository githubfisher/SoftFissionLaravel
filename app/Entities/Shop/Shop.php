<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Shop.
 *
 * @package namespace App\Entities\Shop;
 */
class Shop extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'introduction',
        'headimgurl',
        'mobile',
        'telephone',
        'qrcode_url',
        'wechat',
        'weibo',
        'douyin',
        'location_x',
        'location_y',
        'country',
        'province',
        'city',
        'address',
        'start_at',
        'end_at',
        'details',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function users(): BelongsTo
    {
        return $this->belongsTo('App\Entities\User\User', 'user_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Project', 'shops_projects', 'shop_id', 'project_id');
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Brand', 'shops_projects', 'shop_id', 'brand_id');
    }
}
