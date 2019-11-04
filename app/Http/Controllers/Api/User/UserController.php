<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::user();

        return $this->suc(compact('user'));
    }

    public function resetMobile()
    {

    }

    public function resetEmail()
    {

    }

    public function resetPassword()
    {

    }
}
