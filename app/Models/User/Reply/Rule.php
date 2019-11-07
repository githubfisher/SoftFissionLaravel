<?php
namespace App\Models\User\Reply;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use SoftDeletes;

    protected $table    = 'rules';
    protected $fillable = [
        'user_id', 'app_id', 'scene', 'title', 'reply_rule', 'status', 'start_at', 'end_at',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

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
}
