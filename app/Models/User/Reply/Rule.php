<?php
namespace App\Models\User\Reply;

use App\Models\Model;

class Rule extends Model
{
    protected $table    = 'rules';
    protected $fillable = [
        'user_id', 'app_id', 'scene', 'title', 'reply_rule', 'status', 'start_at', 'end_at',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id');
    }

    public function keywords()
    {
        return $this->hasMany('App\Models\User\Reply\Keyword', 'rule_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\User\Reply\Reply', 'rule_id', 'id');
    }

    /**
     * 查询特定公众号的
     *
     * @param $query
     * @param $scene
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScene($query, $scene)
    {
        return $query->where('scene', $scene);
    }
}
