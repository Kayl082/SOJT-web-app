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
        // Create a shop first
        $shop = \App\Models\Shop::create([
            'name' => 'Sample Grocery Store',
            'description' => 'A local grocery store',
            'address' => '123 Main St',
            'is_active' => true,
        ]);

        // Admin user
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Vendor Owner
        \App\Models\User::create([
            'name' => 'Shop Owner',
            'email' => 'owner@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'vendor_owner',
            'shop_id' => $shop->id,
            'email_verified_at' => now(),
        ]);

        // Vendor Staff
        \App\Models\User::create([
            'name' => 'Shop Staff',
            'email' => 'staff@itinda.com',
            'password' => bcrypt('password'),
            'role' => 'vendor_staff',
            'shop_id' => $shop->id,
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
