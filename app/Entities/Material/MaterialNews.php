<?php
namespace App\Entities\Material;

use App\Models\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class News.
 *
 * @package namespace App\Entities\Material;
 */
class MaterialNews extends Model implements Transformable
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
        return $this->hasMany('App\Entities\Material\MaterialNewsDetail', 'news_id', 'id');
    }
}
