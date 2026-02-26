<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Inventory::class);

        $inventories = Inventory::all();

        return inertia('Vendor/Inventory/Index', [
            'inventories' => $inventories
        ]);
    }

    public function updateStock(Request $request, Inventory $inventory)
    {
        $this->authorize('updateStock', $inventory);

        $request->validate([
            'stock' => ['required', 'integer', 'min:0']
        ]);

        $inventory->update([
            'stock' => $request->stock
        ]);

        return back();
    }

    public function updatePrice(Request $request, Inventory $inventory)
    {
        $this->authorize('updatePrice', $inventory);

        $request->validate([
            'price' => ['required', 'numeric', 'min:0']
        ]);

        $inventory->update([
            'price' => $request->price
        ]);

        return back();
    }
}