<?php
namespace App\Entities\Reply;

use App\Models\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Rule.
 *
 * @package namespace App\Entities\Reply;
 */
class WeRule extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'user_id',
        'app_id',
        'scene',
        'title',
        'reply_rule',
        'status',
        'start_at',
        'end_at',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function maker()
    {
        return $this->belongsTo('App\Entities\User\User', 'user_id');
    }

    public function keywords()
    {
        return $this->hasMany('App\Entities\Reply\WeKeyword', 'rule_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\Entities\Reply\WeReply', 'rule_id', 'id');
    }

    /**
     * 查询特定公众号的
     *
     * @param $query
     * @param $scene
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScene($query, $scene)
    {
        return $query->where('scene', $scene);
    }
}
