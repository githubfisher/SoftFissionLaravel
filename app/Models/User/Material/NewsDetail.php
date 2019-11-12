<?php
namespace App\Models\User\Material;

use App\Models\Model;

class NewsDetail extends Model
{
    protected $table    = 'material_news_detail';
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
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function news()
    {
        return $this->belongsTo('App\Models\User\Material\News', 'news_id');
    }
}
