<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Vendor Owner
        $vendorOwner = \App\Models\User::create([
            'name' => 'Shop Owner',
            'email' => 'owner@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'vendor',
            'email_verified_at' => now(),
        ]);

        // Create a store owned by the vendor
        $store = \App\Models\Shop::create([
            'owner_id' => $vendorOwner->id,
            'store_name' => 'Sample Grocery Store',
            'address' => '123 Main St',
            'city' => 'Sample City',
            'is_active' => true,
        ]);

        // Vendor Staff (also using 'vendor' role)
        \App\Models\User::create([
            'name' => 'Shop Staff',
            'email' => 'staff@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'vendor',
            'email_verified_at' => now(),
        ]);

        // Customer
        \App\Models\User::create([
            'name' => 'Customer User',
            'email' => 'customer@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }
}
