<?php

namespace App\Models\Client;

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
    'shipping_method_id',
    'shipping_cost',
    'discount_code_id',
    'free_shipping_code_id',
    'discount_amount',
];


    protected $casts = [
        'cart_item_ids' => 'array',
        'cart_items_snapshot' => 'array', // Thêm dòng này
    ];
}
