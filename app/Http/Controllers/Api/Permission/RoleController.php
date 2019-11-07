<?php
namespace App\Http\Controllers\Api\Permission;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $guard;
    protected $role;

    public function __construct(\App\Http\Repositories\Permission\Role $role)
    {
        $this->role  = $role;
        $this->guard = Config('auth.defaults.guard');
    }

    public function index()
    {
        $list = $this->role->list(Constant::PAGINATE_MIN, $this->guard);

        return $this->suc(compact('list'));
    }

    public function create(Request $request)
    {
        $role = $this->role->create($request->input('name'), $this->guard);

        return $this->suc(compact('role'));
    }

    public function assignRole($role, $user)
    {
        $role = Role::findOrFail($role);
        $user = User::findOrFail($user);

        if ($res = $this->role->assignRole($role, $user)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function removeRole($role, $user)
    {
        $role = Role::findOrFail($role);
        $user = User::findOrFail($user);

        if ($res = $this->role->removeRole($role, $user)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function allMyRoles()
    {
        $list = $this->role->getAllRoles($this->user());

        return $this->suc(compact('list'));
    }
}
