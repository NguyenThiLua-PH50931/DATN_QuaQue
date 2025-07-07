<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use SoftDeletes;

    protected $table = 'discount_codes';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'active',
        'type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Một mã giảm giá có thể áp dụng cho nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_code_id');
    }

    // Nếu có liên kết sản phẩm qua coupon_product (nếu có)
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'coupon_product', // bảng trung gian đúng tên
            'coupon_id',      // khóa ngoại tới bảng coupon_product (liên kết discount_code/coupon)
            'product_id'      // khóa ngoại tới bảng product
        );
    }
}
