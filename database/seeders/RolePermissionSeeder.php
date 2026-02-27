<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            // Inventory
            'inventory.view',
            'inventory.update_stock',
            'inventory.update_price',

            // Orders
            'orders.view',
            'orders.update_status',

            // Financial
            'financials.view',

            // Staff management
            'staff.manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $owner = Role::firstOrCreate(['name' => 'vendor_owner']);
        $staff = Role::firstOrCreate(['name' => 'vendor_staff']);

        // Owner gets everything
        $owner->syncPermissions($permissions);

        // Staff gets restricted subset
        $staff->syncPermissions([
            'inventory.view',
            'inventory.update_stock',
            'orders.view',
            'orders.update_status',
        ]);
    }
}