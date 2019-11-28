<?php
namespace App\Entities\Menu;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Menu.
 *
 * @package namespace App\Entities\Menu;
 */
class WeMenuDetail extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'menu_id',
        'pid',
        'rule_id',
        'name',
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
        'rule_id' => 0,
        'status'  => 0,
        'pid'     => 0,
    ];

    /**
     * 这个属性应该被转换为原生类型.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo('App\Entities\Menu\WeMenu', 'menu_id');
    }

    public function rule()
    {
        return $this->hasOne('App\Entities\Reply\WeRule', 'id', 'rule_id');
    }
}
