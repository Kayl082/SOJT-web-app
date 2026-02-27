<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Stores
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $stores = Store::where('is_active', true)
            ->select('id', 'store_name', 'city', 'phone')
            ->paginate(12);

        return inertia('customer/Stores', [
            'stores' => $stores
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Single Store + Products
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $store = Store::where('is_active', true)
            ->with([
                'inventory.product'
            ])
            ->findOrFail($id);

        return inertia('customer/StoreDetails', [
            'store' => $store
        ]);
    }
}