<?php
namespace App\Entities\Permission;

use Illuminate\Database\Eloquent\Builder;

class Permission extends \Spatie\Permission\Models\Permission
{
    public function scopeGuardOf(Builder $query, $guard)
    {
        return $query->where('guard_name', $guard);
    }

    public function scopeRecent(Builder $query)
    {
        return $query->orderBy('id', 'desc');
    }
}
