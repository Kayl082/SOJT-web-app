<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorHasStoreAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Admin can access everything
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Must be vendor (owner or staff)
        if (! $user->hasRole('vendor_owner') && ! $user->hasRole('vendor_staff')) {
            abort(403);
        }

        // Must belong to a store
        if ($user->store_id === null) {
            abort(403, 'Vendor has no assigned store.');
        }

        return $next($request);
    }
}