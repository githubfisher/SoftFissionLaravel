<?php
namespace App\Models\User\SuperQrCode;

use App\Models\Model;

class QrCodeDetail extends Model
{
    protected $table    = 'we_qrcode_detail';
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
        return $this->belongsTo('App\Models\User\SuperQrCode\QrCode', 'qrcode_id');
    }
}
