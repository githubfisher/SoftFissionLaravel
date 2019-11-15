<?php
namespace App\Models\User\SuperQrCode;

use App\Models\Model;

class QrCode extends Model
{
    protected $table    = 'we_qrcode';
    protected $fillable = [
        'user_id',
        'app_id',
        'rule_id',
        'title',
        'target_num',
        'num',
        'expire_type',
        'expire_at',
        'expire_in',
        'status',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany('App\Models\User\SuperQrCode\QrCodeDetail', 'qrcode_id', 'id');
    }

    public function rule()
    {
        return $this->hasOne('App\Models\User\Reply\Rule', 'id', 'rule_id');
    }
}
