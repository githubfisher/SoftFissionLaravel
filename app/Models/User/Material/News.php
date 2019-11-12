<?php
namespace App\Models\User\Material;

use App\Models\Model;

class News extends Model
{
    protected $table    = 'news';
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
