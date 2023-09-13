<?php

namespace Tests;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleTestFactory
{
    private array $defaultRoles = ['user', 'admin', 'data entry'];
    private array $defaultPermission = ['show invoice', 'delete invoice', 'edit invoice', 'create invoice'];
    private array $defaultRolesWithPermissions =
    [
        ['name' => 'user', 'permissions' => ''],
        ['name' => 'admin', 'permissions' => ['show invoice', 'delete invoice', 'edit invoice', 'create invoice']]
    ];

    public function createDefaultRoles()
    {
        $this->createRoles($this->defaultRoles);
    }
    public function createRoles(array $roles)
    {
        foreach ($roles as $role)
            Role::create(['name' => $role]);
    }
    public function createDefaultPermissions()
    {
        $this->createPermissions($this->defaultPermission);
    }
    public function createPermissions(array $permissions)
    {
        foreach ($permissions as $permission)
            Permission::create(['name' => $permission]);
    }

    public function assignPermissionsToRoles(array $roles)
    {
        foreach ($roles as $role) {
            Role::where('name', $role['name'])
                ->first()
                ->syncPermissions($role->permissions);
        }
    }

    public function assignPermissionsToRole(string $roleName, array $permissions)
    {
        Role::where('name', $roleName)
            ->first()
            ->syncPermissions($permissions);
    }
    public function makeDefaultRolesAndPermissions()
    {
        $this->createDefaultRoles();
        $this->createDefaultPermissions();
        $this->assignPermissionsToRoles($this->defaultRolesWithPermissions);
    }
}
