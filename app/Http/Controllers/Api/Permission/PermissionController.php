<?php
namespace App\Http\Controllers\Api\Permission;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Permission\CreateRequest;
use App\Repositories\User\UserRepositoryEloquent;
use App\Repositories\Permission\RoleRepositoryEloquent;
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

    public function index(PaginateRequest $request)
    {
        $list = $this->permission->guardOf($this->guard)->paginate($request->input('limit', Constant::PAGINATE_MIN));

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

    public function assignRole($permission, $role, RoleRepositoryEloquent $roleRepositoryEloquent)
    {
        $permission = $this->permission->findOrFail($permission);
        $role       = $roleRepositoryEloquent->findOrFail($role);
        if ($res = $permission->assignRole($role)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function removeRole($permission, $role, RoleRepositoryEloquent $roleRepositoryEloquent)
    {
        $permission = $this->permission->findOrFail($permission);
        $role       = $roleRepositoryEloquent->findOrFail($role);
        if ($res = $permission->removeRole($role)) {
            return $this->suc();
        }

        return $this->err();
    }

    public function allMyPermissons(UserRepositoryEloquent $userRepositoryEloquent)
    {
        $user = $userRepositoryEloquent->find($this->user()->id);
        $list = $user->getAllPermissions();

        return $this->suc(compact('list'));
    }
}
