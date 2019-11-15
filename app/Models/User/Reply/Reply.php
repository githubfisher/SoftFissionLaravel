<?php
namespace App\Models\User\Reply;

use App\Models\Model;

class Reply extends Model
{
    protected $table    = 'we_replies';
    protected $fillable = [
        'rule_id', 'difference', 'reply_type', 'reply_type_female', 'content', 'content_female', 'material_id', 'material_id_female',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function rule()
    {
        return $this->belongsTo('App\Models\User\Reply\Rule', 'rule_id');
    }
}
