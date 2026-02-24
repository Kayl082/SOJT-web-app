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
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // All vendor users belonging to this store
    public function users()
    {
        return $this->hasMany(User::class, 'store_id');
    }

    // Store owner (via Spatie role)
    public function owner()
    {
        return $this->hasOne(User::class, 'store_id')
            ->role('vendor_owner');
    }

    // Store staff
    public function staff()
    {
        return $this->hasMany(User::class, 'store_id')
            ->role('vendor_staff');
    }

    // Products in this store
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    // Orders for this store
    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    // Expenses for this store
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'store_id');
    }

    // Reviews for this store
    public function reviews()
    {
        return $this->hasMany(Review::class, 'store_id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'store_id');
    }
}