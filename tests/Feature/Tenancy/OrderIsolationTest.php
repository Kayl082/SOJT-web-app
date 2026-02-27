<?php

namespace Tests\Feature\Tenancy;

use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Support\StoreContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class OrderIsolationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vendor_cannot_see_other_store_orders()
    {
        // Create required role
        Role::create(['name' => 'vendor_owner']);

        // Create two stores
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();

        // Create a customer (required for foreign key)
        $customer = User::factory()->create();

        // Create vendor assigned to store A
        $vendorA = User::factory()->create([
            'store_id' => $storeA->id,
        ]);

        $vendorA->assignRole('vendor_owner');

        // Create orders for both stores (bypass global scope safely)
        Order::withoutGlobalScopes()->forceCreate([
            'store_id' => $storeA->id,
            'customer_id' => $customer->id,
            'order_number' => 'A-001',
            'status' => 'pending',
            'total_amount' => 100,
            'is_paid' => false,
        ]);

        Order::withoutGlobalScopes()->forceCreate([
            'store_id' => $storeB->id,
            'customer_id' => $customer->id,
            'order_number' => 'B-001',
            'status' => 'pending',
            'total_amount' => 200,
            'is_paid' => false,
        ]);

        // Simulate middleware resolving tenant
        app(StoreContext::class)->set($storeA);

        $orders = Order::all();

        // Assert only store A's order is visible
        $this->assertCount(1, $orders);
        $this->assertEquals($storeA->id, $orders->first()->store_id);
    }
}