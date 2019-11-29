<?php
namespace App\Entities\Shop;

use App\Entities\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Shop.
 *
 * @package namespace App\Entities\Shop;
 */
class ShopsProjects extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'project_id',
    ];

    /**
     * 不可批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];

}
