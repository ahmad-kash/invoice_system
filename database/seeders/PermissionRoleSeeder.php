<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $roles = ['admin', 'user', 'data entry'];

        $permissions = [
            'show invoice', 'create invoice',
            'edit invoice', 'delete invoice',
            'restore invoice', 'force delete invoice',
            'show user', 'create user',
            'edit user', 'delete user',
            'restore user', 'force delete user',
            'reset password',
            'show section', 'create section',
            'edit section', 'delete section',
            'show product', 'create product',
            'edit product', 'delete product',
            'show role', 'create role',
            'edit role', 'delete role',

        ];
        foreach ($permissions as $permission)
            Permission::create(['name' => $permission]);

        foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName]);
            if ($roleName == 'admin')
                $role->syncPermissions(Permission::all());
        }
    }
}
