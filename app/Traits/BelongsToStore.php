<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Support\StoreContext;
use App\Models\User;

trait BelongsToStore
{
    protected static function bootBelongsToStore()
    {
        static::addGlobalScope('store', function (Builder $builder) {

            /** @var User|null $user */
            $user = Auth::user();

            // Admin bypass
            if ($user?->hasRole('admin')) {
                return;
            }

            $storeId = app(StoreContext::class)->id();

            if ($storeId) {
                $builder->where(
                    $builder->getModel()->getTable() . '.store_id',
                    $storeId
                );
            }
        });

        static::creating(function ($model) {

            /** @var User|null $user */
            $user = Auth::user();

            // Admin should manually set store_id
            if ($user?->hasRole('admin')) {
                return;
            }

            $storeId = app(StoreContext::class)->id();

            if ($storeId && empty($model->store_id)) {
                $model->store_id = $storeId;
            }
        });
    }
}