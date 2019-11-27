<?php
namespace App\Entities\Material;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class News.
 *
 * @package namespace App\Entities\Material;
 */
class WeNews extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'app_id',
        'media_id',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany('App\Entities\Material\WeNewsDetail', 'news_id', 'id');
    }
}
