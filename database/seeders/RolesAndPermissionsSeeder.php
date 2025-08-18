<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'request pettycash',
            'view requested pettycash',
            'first pettycash approval',
            'last pettycash approval',
            'approve petycash payments',
            'view cashflow movements',
            'request item purchase',
            'approve item purchase',
            'view reports',
            'view settings',
            'users management settings',
            'update other settings',
            'approve final item purchase',
            'warranty management',
            'mars website management',
            'catalogue management',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $superuser = Role::firstOrCreate(['name' => 'superuser']);
        $basic_user = Role::firstOrCreate(['name' => 'basic_user']);

        // Assign Permissions
        $basic_user->givePermissionTo(['request pettycash']);

        $superuser->givePermissionTo(Permission::all()); // all permissions
    }
}
