<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToStore;

class Review extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = [
        'store_id',
        'customer_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
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

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}