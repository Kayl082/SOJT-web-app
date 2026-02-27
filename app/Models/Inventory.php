<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToStore;

class Inventory extends Model
{
    use HasFactory, BelongsToStore;

    protected $table = 'inventory';

    protected $fillable = [
        'store_id',
        'product_id',
        'stock_level',
        'reorder_level',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}