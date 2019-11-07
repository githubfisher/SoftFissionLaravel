<?php
namespace App\Http\Repositories\Permission;

use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission as Permissions;

class Permission
{
    public function create(string $name, string $guard)
    {
        return Permissions::create(['name' => $name, 'guard_name' => $guard]);
    }

    public function list(int $limit, string $guard)
    {
        return Permissions::where('guard_name', $guard)->simplePaginate($limit);
    }

    public function assignRole(Permissions $permission, Role $role)
    {
        return $permission->assignRole($role);
    }
}
