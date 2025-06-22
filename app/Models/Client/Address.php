<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address',
        'province',
        'district',
        'is_default',
        'created_at',
        'updated_at'
    ];

    // Một địa chỉ thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Một địa chỉ có thể được nhiều đơn hàng sử dụng (lịch sử đơn hàng)
    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
    }
}
