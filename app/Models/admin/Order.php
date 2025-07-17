<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\OrderStatusLog; // thêm ở đầu file nếu chưa có


class Order extends Model
{

    protected $table = 'orders';
    protected $fillable = [
    'order_code',
    'user_id',
    'address_id',
    'shipping_method_id',
    'discount_code_id',
    'discount_amount',
    'total_amount',
    'shipping_cost',
    'status',
    'cancel_reason',
    'payment_method',
    'payment_status',
    // 'bank_transfer_confirmed', 
    'recipient_name',
    'phone',
    'full_address',  // <-- thêm dòng này!
];


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
        return $this->belongsTo(\App\Models\admin\ShippingMethod::class, 'shipping_method_id');
    }

    // Mã giảm giá (nếu có)
    public function discountCode()
{
    return $this->belongsTo(\App\Models\admin\DiscountCode::class, 'discount_code_id');
}

    public function items()
{
    return $this->hasMany(\App\Models\admin\OrderItem::class, 'order_id');
}

    // Bạn có thể thêm các quan hệ khác tương tự nếu cần

public function statusLogs()
{
    return $this->hasMany(OrderStatusLog::class, 'order_id');
}
public function freeShippingCode()
    {
        return $this->belongsTo(DiscountCode::class, 'free_shipping_code_id');
    }
    protected static function boot()
{
    parent::boot();

    static::creating(function ($order) {
        // Chỉ set nếu chưa có, tránh đè giá trị custom nếu có truyền vào
        if (empty($order->order_code)) {
            $order->order_code = 'QQ' . date('Ymd') . '-' . mt_rand(1000, 9999);
        }
    });
}

}
