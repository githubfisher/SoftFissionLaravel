<?php

namespace App\Entities\QrCode;

use App\Models\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class QrCode.
 *
 * @package namespace App\Entities\QrCode;
 */
class WeQrCode extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
