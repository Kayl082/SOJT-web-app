<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $hasStore = false;
        $userRole = null;

        if ($user) {
            // Try to get role from column first, fallback to Spatie roles
            if (isset($user->role)) {
                $userRole = $user->role;
            } else {
                $roles = $user->getRoleNames();
                $userRole = $roles->first() ?? 'customer';
            }

            // Check if vendor has a store
            if ($userRole === 'vendor') {
                $hasStore = \App\Models\Shop::where('owner_id', $user->id)->exists();
            }
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user ? array_merge($user->toArray(), [
                    'role' => $userRole,
                    'roles' => $userRole ? [['name' => $userRole]] : []
                ]) : null,
                'hasStore' => $hasStore,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
