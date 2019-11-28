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
class WeQrcode extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
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

    /**
     *  模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'rule_id'   => 0,
        'type'      => 1,
        'num'       => 0,
        'expire_in' => 0,
        'status'    => 0,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function details()
    {
        return $this->hasMany('App\Entities\QrCode\WeQrcodeDetail', 'qrcode_id', 'id');
    }

    public function rule()
    {
        return $this->hasOne('App\Entities\Reply\WeRule', 'id', 'rule_id');
    }
}
