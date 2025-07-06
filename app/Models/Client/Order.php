<?php

namespace App\Models\Client;

use App\Models\admin\OrderStatusLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client\OrderItem;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    // Danh sách fillable theo cấu trúc bảng
    protected $fillable = [
        'order_code',
        'user_id',
        'address_id',
        'shipping_method_id',
        'discount_code_id',
        'total_amount',
        'shipping_cost',
        'status',
        'is_hidden',
        'payment_method',
        'payment_status',
        'receiver_name',
        'receiver_phone',
        'created_at',
        'updated_at',
        'deleted_at',
        'bank_transfer_confirmed',   // <-- thêm dòng này!
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
        return $this->belongsTo(DiscountCode::class, 'discount_code_id');
    }

    // 1 đơn hàng có nhiều order item (chi tiết sản phẩm trong đơn)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function freeShippingCode()
    {
        return $this->belongsTo(DiscountCode::class, 'free_shipping_code_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class, 'order_id');
    }
}
