<?php

namespace App\Entities\WeChat;

use App\Models\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class App.
 *
 * @package namespace App\Entities\WeChat;
 */
class App extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
