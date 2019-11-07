<?php
namespace App\Models\User\Reply;

use App\Models\Model;

class Keyword extends Model
{
    protected $table    = 'keywords';
    protected $fillable = [
        'rule_id', 'keyword', 'match_type',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function rule()
    {
        return $this->belongsTo('App\Models\User\Reply\Rule', 'rule_id');
    }
}
