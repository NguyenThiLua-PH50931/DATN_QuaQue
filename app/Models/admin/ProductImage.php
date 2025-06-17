<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_url',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getImageUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/no-image.png');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
