<?php
namespace App\Entities\QrCode;

use App\Models\Model;
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

    public function code()
    {
        return $this->belongsTo('App\Entities\QrCode\WeQrcode', 'qrcode_id');
    }
}
