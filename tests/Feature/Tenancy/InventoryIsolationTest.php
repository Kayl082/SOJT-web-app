<?php

namespace Tests\Feature\Tenancy;

use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use App\Support\StoreContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class InventoryIsolationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vendor_cannot_access_other_store_inventory()
    {
        // Create two stores
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();

        // Create global product
        $product = Product::factory()->create();

        // Create vendor assigned to store A
        $vendorA = User::factory()->create([
            'store_id' => $storeA->id,
        ]);

        Role::create(['name' => 'vendor_owner']);
        Role::create(['name' => 'vendor_staff']);
        
        $vendorA->assignRole('vendor_owner');

        // Create inventory records for both stores
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
            'stock_level' => 20,
            'reorder_level' => 5,
            'is_available' => true,
            'price' => 200,
        ]);

        // Simulate middleware resolving store context
        app(StoreContext::class)->set($storeA);

        $inventories = Inventory::all();

        // Assert only store A inventory is visible
        $this->assertCount(1, $inventories);
        $this->assertEquals($storeA->id, $inventories->first()->store_id);

        // Direct find test
        $this->assertNotNull(Inventory::find($inventoryA->id));
        $this->assertNull(Inventory::find($inventoryB->id));
    }
}