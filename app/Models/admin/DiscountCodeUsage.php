<?php
namespace App\Models\admin;


use Illuminate\Database\Eloquent\Model;

class DiscountCodeUsage extends Model
{
    protected $table = 'discount_code_usages';

    protected $fillable = [
        'discount_code_id',
        'user_id',
        'order_id',
        'used_at',
    ];

    // Nếu muốn tự động cast used_at là Carbon (date)
    protected $dates = [
        'used_at',
    ];

    // QUAN HỆ

    // Mã giảm giá liên quan
    public function discountCode()
    {
        return $this->belongsTo(\App\Models\admin\DiscountCode::class, 'discount_code_id');
    }

    // User dùng mã
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Đơn hàng gắn với mã
    public function order()
    {
        return $this->belongsTo(\App\Models\admin\Order::class, 'order_id');
    }
}
