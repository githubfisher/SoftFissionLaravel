<?php
namespace App\Http\Controllers\Api\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryEloquent;
use App\Repositories\Permission\RoleRepositoryEloquent;

class RoleController extends Controller
{
    protected $guard;
    protected $role;

    public function __construct(RoleRepositoryEloquent $role)
    {
        $this->role  = $role;
        $this->guard = Config('auth.defaults.guard');
    }

    public function index()
    {
        $list = $this->role->findWhere(['guard_name' => $this->guard]);

        return $this->suc(compact('list'));
    }

    public function create(Request $request)
    {
        $role = $this->role->create([
            'name'       => $request->input('name'),
            'guard_name' => $this->guard,
        ]);

        return $this->suc(compact('role'));
    }

    public function assignRole($role, $user, UserRepositoryEloquent $userRepositoryEloquent)
    {
        $role = $this->role->findOrFail($role);
        \Log::debug(__FUNCTION__ . ' ' . json_encode(compact('role')));
        $user = $userRepositoryEloquent->findOrFail($user);
        if ($res = $user->assignRole($role)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function removeRole($role, $user, UserRepositoryEloquent $userRepositoryEloquent)
    {
        $role = $role->findOrFail($role);
        $user = $userRepositoryEloquent->findOrFail($user);
        if ($res = $role->removeRole($user)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function allMyRoles(UserRepositoryEloquent $userRepositoryEloquent)
    {
        $user = $userRepositoryEloquent->find($this->user()->id);
        $list = $user->getRoleNames();

        return $this->suc(compact('list'));
    }
}
