<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class PendingPayment extends Model
{
protected $fillable = [
    'user_id',
    'order_id',
    'amount',
    'cart_item_ids',
    'cart_items_snapshot',
    'payment_method',
    'status',
    'recipient_name',
    'phone',
    'full_address',
    'shipping_method',
    'shipping_cost',
    'discount_code',
    'free_shipping_code',
    'discount_amount',
];

    protected $casts = [
        'cart_item_ids' => 'array',
        'cart_items_snapshot' => 'array', // Thêm dòng này
    ];
}
