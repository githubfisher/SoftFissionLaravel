<?php

namespace App\Policies\User\SuperQrCode;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QrCodePolicy
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
