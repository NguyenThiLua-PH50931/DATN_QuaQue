<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
        use SoftDeletes;
    protected $table = 'cart_items';

    // Thêm 'variant_attributes' vào fillable để có thể mass assign được
    protected $fillable = ['user_id', 'product_id', 'price', 'quantity', 'variant_attributes', 'variant_id'];

    protected $casts = [
        'variant_attributes' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

        public function variant()
    {
        // Giả sử bảng product_variants có khóa chính là id
        // và khóa ngoại trong cart_items là variant_id (hoặc tên trường tương ứng)
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
