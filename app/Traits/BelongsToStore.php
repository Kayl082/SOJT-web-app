<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToStore
{
    protected static function bootBelongsToStore()
    {
        static::addGlobalScope('store', function (Builder $builder) {

            if (Auth::check()) {

                $user = Auth::user();

                if ($user && $user->store_id !== null) {
                    $builder->where(
                        $builder->getModel()->getTable() . '.store_id',
                        $user->store_id
                    );
                }
            }
        });
    }
}