<?php
namespace App\Entities;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Comment.
 *
 * @package namespace App\Entities;
 */
class Comment extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'userid',
        'score',
        'body',
        'commentable_id',
        'commentable_type',
        'status',
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
    protected $hidden = [];

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'userid' => 0,
        'score'  => 5.0,
        'body'   => '',
        'status' => 1,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Shop', 'shops_brands', 'brand_id', 'shop_id');
    }
}
