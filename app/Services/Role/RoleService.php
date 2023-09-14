<?php

namespace App\Services\Role;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleService
{
    private ?Collection $permissions = null;

    private function syncPermissions(Role $role, ?array $permissions): void
    {
        if (isset($permissions))
            $role->syncPermissions($permissions);
    }

    public function getAllRoleWithPaginate(): Paginator
    {
        return Role::paginate(5);
    }

    public function create(string $roleName, ?array $permissions): Role
    {
        $role = Role::create(['name' => $roleName]);
        $this->syncPermissions($role, $permissions);

        return $role;
    }
    public function update(Role $role, ?string $roleName, ?array $permissions): Role
    {
        if (isset($roleName))
            $role->update(['name' => $roleName]);
        $this->syncPermissions($role, $permissions);

        return $role;
    }

    public function delete(Role $role)
    {
        return $role->delete();
    }

    public function getPermissionFor(string $filter)
    {
        if (is_null($this->permissions))
            $this->permissions = Permission::all();

        return $this->permissions->filter(fn ($permission) => Str::of($permission->name)->endsWith($filter));
    }
    public function getPermissions()
    {
        $this->permissions = Permission::all();

        $permissionsTypes = ['user', 'invoice', 'role', 'password', 'section', 'product'];

        $permissions = collect();
        foreach ($permissionsTypes as $type) {
            $permissions[$type] = $this->getPermissionFor($type);
        }
        return $permissions;
    }
}
