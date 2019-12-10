<?php
namespace App\Entities\Permission;

use Illuminate\Database\Eloquent\Builder;

class Permission extends \Spatie\Permission\Models\Permission
{
    public function scopeGuardOf(Builder $query, $guard)
    {
        return $query->where('guard_name', $guard);
    }
}
