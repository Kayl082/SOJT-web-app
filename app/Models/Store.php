<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'store_name',
        'address',
        'city',
        'latitude',
        'longitude',
        'phone',
        'operating_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * All users (vendor owner + staff) assigned to this store.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'store_id');
    }

    /**
     * Store owner (Spatie role: vendor_owner).
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'store_id')
            ->role('vendor_owner');
    }

    /**
     * Store staff (Spatie role: vendor_staff).
     */
    public function staff()
    {
        return $this->hasMany(User::class, 'store_id')
            ->role('vendor_staff');
    }

    /**
     * Inventory entries for this store.
     * (Product is accessed through inventory → product)
     */
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'store_id');
    }

    /**
     * Orders belonging to this store.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    /**
     * Expenses belonging to this store.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'store_id');
    }

    /**
     * Reviews for this store (store + product scoped).
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'store_id');
    }
}