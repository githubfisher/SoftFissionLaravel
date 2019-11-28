<?php
namespace App\Entities\Reply;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WeReply.
 *
 * @package namespace App\Entities\Reply;
 */
class WeReply extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'rule_id',
        'difference',
        'reply_type',
        'reply_type_female',
        'content',
        'content_female',
        'mini_appid',
        'pagepath',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'rule_id'           => 0,
        'difference'        => 0,
        'reply_type'        => 1,
        'reply_type_female' => 0,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'difference' => 'boolean',
    ];

    public function rule()
    {
        return $this->belongsTo('App\Entities\Reply\WeRule', 'rule_id');
    }
}
