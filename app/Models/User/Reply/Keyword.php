<?php
namespace App\Models\User\Reply;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use SoftDeletes;

    protected $table    = 'keywords';
    protected $fillable = [
        'rule_id', 'keyword', 'match_type',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    public function rule()
    {
        return $this->belongsTo('App\Models\User\Reply\Rule', 'rule_id');
    }
}
