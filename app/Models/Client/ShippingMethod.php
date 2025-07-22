<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $table = 'shipping_methods';

    protected $fillable = [
        'name',
        'description',
        'cost',
        'estimated_days',
        'active',
        'created_at',
        'updated_at'
    ];

    // Một phương thức giao hàng có thể được nhiều đơn hàng sử dụng
    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_method');
    }
}
