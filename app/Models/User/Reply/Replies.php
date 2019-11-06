<?php
namespace App\Models\User\Reply;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Replies extends Model
{
    use SoftDeletes;

    protected $table   = 'replies';
    protected $hidden  = [];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    public function rule()
    {
        return $this->belongsTo('App\Models\User\Reply\Rules', 'rule_id');
    }
}
