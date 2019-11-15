<?php
namespace App\Http\Controllers\Api\Permission;

use App\Utilities\FeedBack;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Permission\CreateRequest;
use App\Repositories\Permission\PermissionRepositoryEloquent;

class PermissionController extends Controller
{
    protected $guard;
    protected $permission;

    public function __construct(PermissionRepositoryEloquent $permission)
    {
        $this->permission = $permission;
        $this->guard      = Config('auth.defaults.guard');
    }

    public function index()
    {
        $list = $this->permission->findWhere(['guard_name' => $this->guard]);

        return $this->suc(compact('list'));
    }

    public function create(CreateRequest $request)
    {
        $role = $this->permission->create([
            'name'       => $request->input('name'),
            'guard_name' => $this->guard,
        ]);
        if ($role) {
            return $this->suc(compact('role'));
        }

        return $this->err(FeedBack::CREATE_FAIL);
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
