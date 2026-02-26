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
        // Clear cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ================================
        // Create Roles
        // ================================
        $admin  = Role::findOrCreate('admin');
        $owner  = Role::findOrCreate('vendor_owner');
        $staff  = Role::findOrCreate('vendor_staff');
        $customer = Role::findOrCreate('customer');

        // ================================
        // Define All Permissions
        // ================================

        $permissions = [

            // Orders
            'view_orders',
            'update_order_status',

            // Inventory
            'manage_inventory',
            'update_stock',

            // Products
            'manage_products',
            'modify_prices',

            // Financial
            'view_financials',
            'view_expenses',
            'view_profit',

            // Staff management
            'manage_staff',

            // Analytics
            'view_basic_analytics',
            'view_advanced_analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // ================================
        // Assign Permissions
        // ================================

        // ADMIN → full access
        $admin->syncPermissions(Permission::all());

        // OWNER → everything except system-level admin stuff
        $owner->syncPermissions([
            'view_orders',
            'update_order_status',
            'manage_inventory',
            'update_stock',
            'manage_products',
            'modify_prices',
            'view_financials',
            'view_expenses',
            'view_profit',
            'manage_staff',
            'view_basic_analytics',
            'view_advanced_analytics',
        ]);

        // STAFF → restricted access
        $staff->syncPermissions([
            'view_orders',
            'update_order_status',
            'manage_inventory',
            'update_stock',
            'manage_products',
            'view_basic_analytics',
        ]);

        // CUSTOMER → no backend permissions
        $customer->syncPermissions([]);
    }
}