<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\StoreContext;
use Symfony\Component\HttpFoundation\Response;

class ResolveStoreTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only resolve tenancy for vendor roles
        if ($user && $user->hasAnyRole(['vendor_owner', 'vendor_staff'])) {

            $store = $user->store;

            if (!$store) {
                abort(403, 'Vendor has no store assigned.');
            }

            if (!$store->is_active) {
                abort(403, 'Store is not available.');
            }

            app(StoreContext::class)->set($store);
        }

        return $next($request);
    }
}