<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Order;   // Import model Order từ namespace admin
use App\Models\admin\Product; // Import model Product từ namespace admin

class OrderItem extends Model
{
    protected $table = 'order_items';

    // Quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Quan hệ với Product (nếu cần)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
