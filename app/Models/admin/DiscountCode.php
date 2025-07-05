<?php

namespace App\Models\admin;

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
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'active' => 'boolean',
    ];

    public function isValid(): bool
    {
        $now = now();

        return $this->active &&
               $this->start_date <= $now &&
               $this->end_date >= $now &&
               $this->used_count < $this->usage_limit;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid() || $subtotal < $this->min_order_amount) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = ($subtotal * $this->discount_value) / 100;

            if (!is_null($this->max_discount_amount)) {
                $discount = min($discount, $this->max_discount_amount);
            }

        } elseif ($this->discount_type === 'fixed') {
            $discount = $this->discount_value;

        } else {
            $discount = 0;
        }

        return round($discount, 2);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_code_id');
    }
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
