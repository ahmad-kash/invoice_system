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
        $roles = ['admin', 'user'];

        $permissions = [
            'show invoice', 'create invoice',
            'delete invoice', 'edit invoice',
            'force delete invoice', 'restore invoice',
            'create section', 'show section',
            'delete section', 'edit section',
            'create product', 'show product',
            'delete product', 'edit product'
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
