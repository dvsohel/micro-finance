<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            'reports.view',

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('Super Admin', 'web');
        $admin = Role::findOrCreate('Admin', 'web');
        $manager = Role::findOrCreate('Manager', 'web');
        $staff = Role::findOrCreate('Staff', 'web');

        $superAdmin->syncPermissions($permissions);

        $admin->syncPermissions([
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'products.view',
            'products.create',
            'products.edit',
            'reports.view',
        ]);

        $manager->syncPermissions([
            'dashboard.view',
            'products.view',
            'products.create',
            'products.edit',
            'reports.view',
        ]);

        $staff->syncPermissions([
            'dashboard.view',
            'products.view',
        ]);
    }
}
