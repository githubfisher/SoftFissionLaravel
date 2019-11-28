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
        'introduction' => null,
        'headimgurl'   => null,
        'telephone'    => null,
        'qrcode_url'   => null,
        'wechat'       => null,
        'weibo'        => null,
        'douyin'       => null,
        'location_x'   => 0,
        'location_y'   => 0,
        'country'      => '中国',
        'province'     => '河北',
        'city'         => '保定',
        'address'      => null,
        'start_at'     => '00:00',
        'end_at'       => '00:00',
        'details'      => null,
    ];

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

    /**
     * 获取此文章的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Entities\Comment', 'commentable');
    }
}
