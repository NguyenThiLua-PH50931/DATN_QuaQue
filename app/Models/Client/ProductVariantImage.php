<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    protected $table = 'product_variant_images'; // Đảm bảo tên đúng với bảng
    protected $guarded = []; // Tùy chọn, thường để trống nếu muốn mass assign

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
