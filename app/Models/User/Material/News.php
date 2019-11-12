<?php
namespace App\Models\User\Material;

use App\Models\Model;

class News extends Model
{
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
