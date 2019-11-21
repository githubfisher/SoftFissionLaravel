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

    public function rule()
    {
        return $this->belongsTo('App\Entities\Reply\WeRule', 'rule_id');
    }
}
