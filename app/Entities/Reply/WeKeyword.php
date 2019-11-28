<?php
namespace App\Entities\Reply;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WeKeyword.
 *
 * @package namespace App\Entities\Reply;
 */
class WeKeyword extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'rule_id',
        'keyword',
        'match_type',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'rule_id' => 0,
        'match_type' => 1,
    ];

    public function rule()
    {
        return $this->belongsTo('App\Entities\Reply\WeRule', 'rule_id');
    }
}
