<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    protected $fillable = ['user_id', 'product_id'];

    /**
     * Quan hệ với model User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Quan hệ với model Product
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Client\Product::class, 'product_id');
    }
}
