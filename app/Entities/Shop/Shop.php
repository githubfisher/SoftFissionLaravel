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
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
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
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'location_x'   => 0,
        'location_y'   => 0,
        'country'      => '中国',
        'province'     => '河北',
        'city'         => '保定',
        'start_at'     => '00:00',
        'end_at'       => '00:00',
    ];

    /**
     * 获取店铺所有者
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo('App\Entities\User\User', 'user_id');
    }

    /**
     * 获取店铺经营的项目
     *
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Project', 'shops_projects', 'shop_id', 'project_id');
    }

    /**
     * 获取店铺经营的品牌
     *
     * @return BelongsToMany
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Brand', 'shops_brands', 'shop_id', 'brand_id');
    }

    /**
     * 获取店铺的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Entities\Comment', 'commentable');
    }
}
