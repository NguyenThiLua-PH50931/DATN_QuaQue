<?php

namespace App\Models\Client;

use App\Models\admin\OrderStatusLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client\OrderItem;

class Order extends Model
{

    protected $table = 'orders';

    // Danh sách fillable theo cấu trúc bảng
protected $fillable = [
    'order_code',
    'user_id',
    'recipient_name',
    'phone',
    'full_address',
    'shipping_method',
    'shipping_cost',
    'free_shipping_code',
    'discount_code',
    'discount_amount',
    'total_amount',
    'payment_method',
    'payment_ref',
    'payment_txn_id',
    'payment_status',
    'refund_status',
    'refund_amount',
    'refund_ref',
    'refund_message',
    'refunded_at',
    'status',
    'cancel_reason',
    'zp_trans_id',
    'created_at',
    'updated_at',
];


    /** Relationships */

    // 1 đơn hàng thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    // 1 đơn hàng có 1 địa chỉ nhận hàng
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // 1 đơn hàng có 1 phương thức giao hàng
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    // 1 đơn hàng có thể có mã giảm giá
    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class, 'discount_code');
    }

    // 1 đơn hàng có nhiều order item (chi tiết sản phẩm trong đơn)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function freeShippingCode()
    {
        return $this->belongsTo(DiscountCode::class, 'free_shipping_code');
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class, 'order_id');
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
public function zlpAppTransId(): string
{
    $yymmdd = ($this->created_at ?? now())->format('ymd'); // yymmdd
    $base   = preg_replace('/[^A-Za-z0-9_\-]/', '', (string)$this->order_code);
    return substr($yymmdd . '_' . $base, 0, 40);
}

}

