<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'category_id',
        'region_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'origin'
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class);
    }
    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class, 'seller_id');
    }
    public function images()
    {
        return $this->hasMany(\App\Models\ProductImage::class);
    }
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }
}
