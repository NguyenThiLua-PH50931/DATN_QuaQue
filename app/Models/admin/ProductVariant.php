<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'description', 'price', 'stock', 'sku', 'barcode', 'image', 'active'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
