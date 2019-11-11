<?php
namespace App\Models\User\SuperQrCode;

use Illuminate\Database\Eloquent\Model;

class QrCodeDetail extends Model
{
    protected $table    = 'super_qr_code_detail';
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
