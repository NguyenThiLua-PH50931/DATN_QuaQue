<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    // Quan hệ với User (đơn hàng thuộc về 1 user)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Nếu bạn muốn lấy địa chỉ
    public function address()
    {
        return $this->belongsTo(\App\Models\admin\Address::class, 'address_id');
    }

    // Phương thức giao hàng
    public function shippingMethod()
    {
        return $this->belongsTo(\App\Models\ShippingMethod::class, 'shipping_method_id');
    }

    // Mã giảm giá (nếu có)
    public function discountCode()
    {
        return $this->belongsTo(\App\Models\DiscountCode::class, 'discount_code_id');
    }
    public function items()
{
    return $this->hasMany(\App\Models\admin\OrderItem::class, 'order_id');
}

    // Bạn có thể thêm các quan hệ khác tương tự nếu cần
    protected static function boot()
{
    parent::boot();

    static::creating(function ($order) {
        $order->order_code = 'QQ' . date('Ymd') . '-' . mt_rand(1000, 9999);
    });
}

}
