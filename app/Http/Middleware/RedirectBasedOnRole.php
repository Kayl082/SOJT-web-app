<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $role = $request->user()->role;

            // Redirect to appropriate dashboard based on role
            if ($request->is('dashboard')) {
                return match($role) {
                    'admin' => redirect()->route('admin.dashboard'),
                    'vendor_owner', 'vendor_staff' => redirect()->route('vendor.dashboard'),
                    'customer' => redirect()->route('customer.dashboard'),
                    default => $next($request),
                };
            }
        }

        return $next($request);
    }
}
