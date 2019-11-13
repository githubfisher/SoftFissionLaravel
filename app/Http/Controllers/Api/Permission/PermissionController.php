<?php
namespace App\Http\Controllers\Api\Permission;

use App\Utilities\Constant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $guard;
    protected $permission;

    public function __construct(\App\Http\Repositories\Permission\Permission $permission)
    {
        $this->permission = $permission;
        $this->guard      = Config('auth.defaults.guard');
    }

    public function index()
    {
        $list = $this->permission->list(Constant::PAGINATE_MIN, $this->guard);

        return $this->suc(compact('list'));
    }

    public function create(Request $request)
    {
        $role = $this->permission->create($request->input('name'), $this->guard);

        return $this->suc(compact('role'));
    }

    public function assignRole($permission, $role)
    {
        $permission = Permission::findOrFail($permission);
        $role       = Role::findOrFail($role);

        if ($res = $this->permission->assignRole($permission, $role)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function removeRole($permission, $role)
    {
        $permission = Permission::findOrFail($permission);
        $role       = Role::findOrFail($role);

        if ($res = $this->permission->removeRole($permission, $role)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function allMyPermissons()
    {
        $list = $this->permission->getAllPermissions($this->user());

        return $this->suc(compact('list'));
    }
}
