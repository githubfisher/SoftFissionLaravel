<?php

namespace App\Policies\User\Shop;

use App\Entities\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
