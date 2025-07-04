<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_variant_value_id',
        'variant_id', 'variant_name', 'product_variant_value_name',
        'product_sku', 'barcode', 'product_image',
        'quantity', 'price', 'discount', 'total', 'status', 'note'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
public function productVariant()
{
    return $this->belongsTo(ProductVariant::class, 'product_variant_value_id');
}


}