<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreSetupController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->hasRole('vendor_owner')) {
            abort(403);
        }

        if ($user->store_id !== null) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'You already have a store.');
        }

        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
        ]);

        $store = Store::create([
            'store_name' => $validated['store_name'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        $user->update([
            'store_id' => $store->id
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Store created successfully!');
    }
}