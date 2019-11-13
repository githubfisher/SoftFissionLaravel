<?php
namespace App\Entities\Material;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class News.
 *
 * @package namespace App\Entities\Material;
 */
class News extends Model implements Transformable
{
    use TransformableTrait;

    protected $table    = 'material_news';
    protected $fillable = [
        'user_id',
        'app_id',
        'media_id',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany('App\Models\User\Material\NewsDetail', 'news_id', 'id');
    }
}
