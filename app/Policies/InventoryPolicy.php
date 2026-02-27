<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inventory;
use App\Services\StoreContext;

class InventoryPolicy
{
    public function view(User $user, Inventory $inventory): bool
    {
        return $inventory->store_id === app(StoreContext::class)->id()
            && $user->can('inventory.view');
    }

    public function updateStock(User $user, Inventory $inventory): bool
    {
        return $inventory->store_id === app(StoreContext::class)->id()
            && $user->can('inventory.update_stock');
    }

    public function updatePrice(User $user, Inventory $inventory): bool
    {
        return $inventory->store_id === app(StoreContext::class)->id()
            && $user->can('inventory.update_price');
    }
}