<?php

namespace Tests\Feature\Tenancy;

use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Support\StoreContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AdminBypassTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_see_all_store_orders()
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'vendor_owner']);

        // Create two stores
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();

        // Create customer
        $customer = User::factory()->create();

        // Create admin
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create vendor assigned to store A
        $vendorA = User::factory()->create([
            'store_id' => $storeA->id,
        ]);
        $vendorA->assignRole('vendor_owner');

        // Create orders for both stores
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

        /*
        |--------------------------------------------------------------------------
        | Vendor View (Scoped)
        |--------------------------------------------------------------------------
        */

        app(StoreContext::class)->set($storeA);

        $vendorOrders = Order::all();

        $this->assertCount(1, $vendorOrders);
        $this->assertEquals($storeA->id, $vendorOrders->first()->store_id);

        /*
        |--------------------------------------------------------------------------
        | Admin View (No StoreContext)
        |--------------------------------------------------------------------------
        */

        // Clear StoreContext (simulate admin request)
        app(StoreContext::class)->clear();

        $adminOrders = Order::withoutGlobalScopes()->get();

        $this->assertCount(2, $adminOrders);
    }
}