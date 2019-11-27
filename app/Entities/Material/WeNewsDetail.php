<?php
namespace App\Entities\Material;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class NewsDetail.
 *
 * @package namespace App\Entities\Material;
 */
class WeNewsDetail extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'news_id',
        'thumb_media_id',
        'sort',
        'show_cover_pic',
        'title',
        'author',
        'digest',
        'thumb_url',
        'url',
        'content_source_url',
        'content',
        'poster_id',
        'image_id',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function news()
    {
        return $this->belongsTo('App\Entities\Material\WeNews', 'news_id');
    }
}
