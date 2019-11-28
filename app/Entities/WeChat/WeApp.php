<?php
namespace App\Entities\WeChat;

use App\Entities\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class App.
 *
 * @package namespace App\Entities\WeChat;
 */
class WeApp extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

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
        'deleted_at',
        'funcscope_category',
    ];
    protected $hidden  = ['alias','principal_name','signature'];
    protected $guarded = ['id'];
    protected $dates   = ['deleted_at'];

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'keyword_reply'   => 0,
        'anytype_reply'   => 0,
        'subscribe_reply' => 0,
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo('App\Entities\User\User', 'user_id');
    }
}
