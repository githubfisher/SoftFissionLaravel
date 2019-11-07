<?php
namespace App\Http\Repositories\Permission;

use App\Models\User\User;
use \Spatie\Permission\Models\Role as Roles;

class Role
{
    public function create(string $name, string $guard)
    {
        return Roles::create(['name' => $name, 'guard_name' => $guard]);
    }

    public function list(int $limit, string $guard)
    {
        return Roles::where('guard_name', $guard)->simplePaginate($limit);
    }

    public function assignRole(Roles $role, User $user)
    {
        return $user->assignRole($role);
    }

    public function removeRole(Roles $role, User $user)
    {
        return $user->removeRole($role);
    }

    public function getAllRoles(User $user)
    {
        return $user->getRoleNames();
    }
}
