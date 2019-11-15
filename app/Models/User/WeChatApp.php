<?php
namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeChatApp extends Model
{
    use SoftDeletes;

    protected $table    = 'we_app';
    protected $fillable = [
        'user_id',
        'app_id',
        'nick_name',
        'head_img',
        'user_name',
        'qrcode_url',
        'refresh_token',
        'service_type_info',
        'verify_type_info',
        'alias',
        'principal_name',
        'signature',
        'keyword_reply',
        'anytype_reply',
        'subscribe_reply',
    ];
    protected $hidden  = ['alias','principal_name','signature'];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id');
    }
}
