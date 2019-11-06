<?php
namespace App\Models\Message;

use App\Models\Model;

class Mail extends Model
{
    protected $table    = 'mail';
    protected $fillable = [
        'user_id',
        'scene_code', // 情景编码
        'title',
        'content',
        'status',
    ];
    protected $hidden = [];

    /**
     * 查询未读的消息
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 0);
    }
}
