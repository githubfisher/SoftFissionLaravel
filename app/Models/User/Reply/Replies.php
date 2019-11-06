<?php
namespace App\Models\User\Reply;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Replies extends Model
{
    use SoftDeletes;

    protected $table    = 'replies';
    protected $fillable = [
        'rule_id', 'difference', 'reply_type', 'reply_type_female', 'content', 'content_female', 'material_id', 'material_id_female',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    public function rule()
    {
        return $this->belongsTo('App\Models\User\Reply\Rules', 'rule_id');
    }
}
