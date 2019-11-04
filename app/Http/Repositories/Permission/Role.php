<?php
namespace App\Http\Repositories\Permission;

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
}
