<?php
namespace App\Models\User\Reply;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rules extends Model
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
}
