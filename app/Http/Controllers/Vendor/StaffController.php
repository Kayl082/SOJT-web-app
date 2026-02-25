<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Staff (Owner Only)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $owner = $request->user();

        if (! $owner->hasRole('vendor_owner')) {
            abort(403);
        }

        $staff = User::where('store_id', $owner->store_id)
            ->role('vendor_staff')
            ->select('id', 'name', 'email', 'created_at')
            ->get();

        return response()->json($staff);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Staff (Owner Only)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $owner = $request->user();

        if (! $owner->hasRole('vendor_owner')) {
            abort(403);
        }

        if ($owner->store_id === null) {
            abort(403, 'Owner must have a store first.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
        ]);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'store_id' => $owner->store_id,
        ]);

        $staff->assignRole('vendor_staff');

        return response()->json([
            'message' => 'Staff created successfully.',
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
            ]
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Staff (Owner Only)
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request, $id)
    {
        $owner = $request->user();

        if (! $owner->hasRole('vendor_owner')) {
            abort(403);
        }

        $staff = User::where('store_id', $owner->store_id)
            ->role('vendor_staff')
            ->findOrFail($id);

        if ($staff->id === $owner->id) {
            abort(403, 'You cannot delete yourself.');
        }

        $staff->delete();

        return response()->json([
            'message' => 'Staff removed successfully.'
        ]);
    }
}