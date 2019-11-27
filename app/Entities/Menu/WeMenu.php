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
        'status',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany('App\Entities\Menu\WeMenuDetail', 'menu_id');
    }

    public function setFilterAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['filter'] = json_encode($value);
        } else {
            $this->attributes['filter'] = null;
        }
    }

    public function getFilterAttribute($value)
    {
        if ( ! empty($value)) {
            $this->attributes['filter'] = json_decode($value, true);
        }
    }
}
