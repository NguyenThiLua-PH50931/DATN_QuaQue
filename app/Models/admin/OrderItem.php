<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Order;
use App\Models\admin\Product;

use App\Models\admin\ProductVariant;

class OrderItem extends Model
{
    protected $table = 'order_items';

    // **THÊM fillable vào đây**
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_variant_value_id',
        'product_variant_value_name',
        'product_sku',
        'product_image',
        'quantity',
        'price',
        'total',
    ];

    // Quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_value_id');
    }
    
}
