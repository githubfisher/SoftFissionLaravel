<?php

namespace App\Entities\Shop;

use App\Entities\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

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
        'keyword',
        'match_type',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function users(): BelongsTo
    {
        return $this->belongsTo('App\Entities\User\User', 'user_id');
    }

}
