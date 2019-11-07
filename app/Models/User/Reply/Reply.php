<?php
namespace App\Models\User\Reply;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
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
        return $this->belongsTo('App\Models\User\Reply\Rule', 'rule_id');
    }
}
