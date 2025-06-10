<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_url'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
