<?php
namespace App\Models\User\Reply;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keywords extends Model
{
    use SoftDeletes;

    protected $table   = 'keywords';
    protected $hidden  = [];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    public function rule()
    {
        return $this->belongsTo('App\Models\User\Reply\Rules', 'rule_id');
    }
}