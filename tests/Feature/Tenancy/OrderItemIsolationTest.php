<?php

namespace Tests\Feature\Tenancy;

use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\OrderItem;
use App\Support\StoreContext;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderItemIsolationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_items_are_isolated_via_their_order()
    {
        // Create two stores
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();

        // Create customer
        $customer = User::factory()->create();

        // Create global product
        $product = Product::factory()->create();

        // Create inventory for each store
        $inventoryA = Inventory::withoutGlobalScopes()->forceCreate([
            'store_id' => $storeA->id,
            'product_id' => $product->id,
            'stock_level' => 10,
            'reorder_level' => 5,
            'is_available' => true,
            'price' => 100,
        ]);

        $inventoryB = Inventory::withoutGlobalScopes()->forceCreate([
            'store_id' => $storeB->id,
            'product_id' => $product->id,
            'stock_level' => 10,
            'reorder_level' => 5,
            'is_available' => true,
            'price' => 200,
        ]);

        // Create orders
        $orderA = Order::withoutGlobalScopes()->forceCreate([
            'store_id' => $storeA->id,
            'customer_id' => $customer->id,
            'order_number' => 'A-001',
            'status' => 'pending',
            'total_amount' => 100,
            'is_paid' => false,
        ]);

        $orderB = Order::withoutGlobalScopes()->forceCreate([
            'store_id' => $storeB->id,
            'customer_id' => $customer->id,
            'order_number' => 'B-001',
            'status' => 'pending',
            'total_amount' => 200,
            'is_paid' => false,
        ]);

        // Create order items matching real schema
        OrderItem::create([
            'order_id' => $orderA->id,
            'inventory_id' => $inventoryA->id,
            'quantity' => 1,
            'unit_price' => 100,
            'subtotal' => 100,
        ]);

        OrderItem::create([
            'order_id' => $orderB->id,
            'inventory_id' => $inventoryB->id,
            'quantity' => 2,
            'unit_price' => 200,
            'subtotal' => 400,
        ]);

        // Simulate StoreContext
        app(StoreContext::class)->set($storeA);

        $orders = Order::with('items')->get();

        // Only store A order should be visible
        $this->assertCount(1, $orders);
        $this->assertEquals($storeA->id, $orders->first()->store_id);

        // And only its own item
        $this->assertCount(1, $orders->first()->items);
        $this->assertEquals(1, $orders->first()->items->first()->quantity);
        $this->assertEquals(100, $orders->first()->items->first()->unit_price);
    }
}