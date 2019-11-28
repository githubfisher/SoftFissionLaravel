<?php
namespace App\Entities\QrCode;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class QrCode.
 *
 * @package namespace App\Entities\QrCode;
 */
class WeQrcodeDetail extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'qrcode_id',
        'batch',
        'scene_str',
        'scan_num',
        'subscribe_num',
        'url',
        'ticket',
        'expire_at',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'scene_str'     => '',
        'url'           => '',
        'ticket'        => '',
        'scan_num'      => 0,
        'subscribe_num' => 0,
    ];

    public function code()
    {
        return $this->belongsTo('App\Entities\QrCode\WeQrcode', 'qrcode_id');
    }
}
