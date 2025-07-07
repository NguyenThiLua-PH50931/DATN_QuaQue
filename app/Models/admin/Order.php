<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\admin\OrderStatusLog; // thêm ở đầu file nếu chưa có


class Order extends Model
{
    use SoftDeletes;

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
    'payment_method',
    'payment_status',
    'is_hidden',
    'bank_transfer_confirmed', 
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
    protected static function boot()
{
    parent::boot();

    static::creating(function ($order) {
        $order->order_code = 'QQ' . date('Ymd') . '-' . mt_rand(1000, 9999);
    });
}
public function statusLogs()
{
    return $this->hasMany(OrderStatusLog::class, 'order_id');
}
}
