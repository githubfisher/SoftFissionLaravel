<?php
namespace App\Entities\Menu;

use App\Models\Model;
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

    public function menu()
    {
        return $this->belongsTo('App\Entities\Menu\WeMenu', 'menu_id');
    }

    public function rule()
    {
        return $this->hasOne('App\Entities\Reply\WeRule', 'id', 'rule_id');
    }
}
