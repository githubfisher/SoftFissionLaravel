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
class WeMenu extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'app_id',
        'type',
        'filter',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany('App\Entities\Menu\WeMenuDetail', 'menu_id');
    }
}
