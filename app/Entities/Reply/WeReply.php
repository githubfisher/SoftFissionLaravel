<?php

namespace App\Entities\Reply;

use App\Models\Model;
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
        'material_id',
        'material_id_female',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function rule()
    {
        return $this->belongsTo('App\Entities\Reply\WeRule', 'rule_id');
    }

}
