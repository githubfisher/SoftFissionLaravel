<?php
namespace App\Entities;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id',
        'score',
        'body',
        'commentable_id',
        'commentable_type',
        'status',
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
        'score'  => 5,
        'status' => 0,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo('App\Entities\User\User', 'user_id', 'id');
    }

    /**
     * 获取拥有此评论的模型。
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}
