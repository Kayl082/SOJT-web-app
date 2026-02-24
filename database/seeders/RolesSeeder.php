<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'admin',
            'vendor_owner',
            'vendor_staff',
            'customer',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}