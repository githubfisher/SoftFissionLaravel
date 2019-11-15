<?php

namespace App\Entities\WeChat;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class App.
 *
 * @package namespace App\Entities\WeChat;
 */
class App extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

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
}
