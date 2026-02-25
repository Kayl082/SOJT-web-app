<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions
        $permissions = [
            'view products',
            'create products',
            'update products',
            'update stock',
            'modify price',
            'view orders',
            'update order status',
            'view financials',
            'manage staff',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $owner = Role::firstOrCreate(['name' => 'vendor_owner']);
        $staff = Role::firstOrCreate(['name' => 'vendor_staff']);

        // Owner gets everything
        $owner->givePermissionTo($permissions);

        // Staff gets limited permissions
        $staff->givePermissionTo([
            'view products',
            'create products',
            'update products',
            'update stock',
            'view orders',
            'update order status',
        ]);
    }
}