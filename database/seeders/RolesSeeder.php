<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
        {
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            // Roles
            $admin = Role::findOrCreate('admin');
            $owner = Role::findOrCreate('vendor_owner');
            $staff = Role::findOrCreate('vendor_staff');
            $customer = Role::findOrCreate('customer');

            // Staff permissions
            $staffPermissions = [
                'view_orders',
                'update_order_status',
                'manage_inventory',
                'update_stock',
                'view_basic_analytics',
            ];

            foreach ($staffPermissions as $permission) {
                Permission::findOrCreate($permission);
            }

            // Assign to staff
            $staff->syncPermissions($staffPermissions);
        }
}