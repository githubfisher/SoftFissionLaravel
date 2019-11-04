<?php
namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeChatApp extends Model
{
    use SoftDeletes;

    protected $table   = 'wechat_app';
    protected $hidden  = ['alias','principal_name','signature'];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id');
    }
}
