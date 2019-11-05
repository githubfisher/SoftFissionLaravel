<?php
namespace App\Models\User\Message;

use Illuminate\Database\Eloquent\Model;

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
     * 查询特定账户的消息
     *
     * @param $query
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocal($query, $userId)
    {
        return $query->where('user_id', $userId)->orderBy('id', 'desc');
    }

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
