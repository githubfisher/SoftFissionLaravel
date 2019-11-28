<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Project.
 *
 * @package namespace App\Entities\Shop;
 */
class Project extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    protected $hidden  = [];
    protected $guarded = ['id'];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany('App\Entities\Shop\Shop', 'shops_projects', 'brand_id', 'project_id');
    }
}
