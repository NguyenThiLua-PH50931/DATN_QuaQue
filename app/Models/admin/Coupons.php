<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'discount_codes'; // tên bảng trong database

    // Các cột có thể gán dữ liệu hàng loạt (fillable)
    protected $fillable = [
        'code',
        'description',
        'discount_type',      // 'percent' hoặc 'fixed'
        'discount_value',     // giá trị giảm
        'min_order_amount',   // đơn hàng tối thiểu
        'max_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',        // số lượt sử dụng tối đa
        'used_count',         // số lượt đã dùng
        'active',             // 0: ẩn, 1: hiện
    ];

    // Kiểu dữ liệu cần ép (casting)
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'float',
        'min_order_amount' => 'float',
        'max_discount_amount' => 'float',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product', 'coupon_id', 'product_id');
    }
}
