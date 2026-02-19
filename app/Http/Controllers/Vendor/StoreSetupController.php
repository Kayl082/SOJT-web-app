<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class StoreSetupController extends Controller
{
    /**
     * Store the new store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'operating_hours' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        // Create store with current user as owner
        Shop::create([
            'owner_id' => $request->user()->id,
            'store_name' => $validated['store_name'],
            'address' => $validated['address'],
            'city' => $validated['city'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Store created successfully! Welcome to iTinda.');
    }
}
