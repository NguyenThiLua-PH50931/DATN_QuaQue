<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'product_variant_value_id',
        'rating',
        'content',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariantAttributeValue::class, 'product_variant_value_id');
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    } // của đánh giá
    public function productVariantId()
    {
        // product_variant_value_id là FK tới bảng product_variants (id)
        return $this->belongsTo(ProductVariant::class, 'product_variant_value_id');
    } // của đổ đánh gia
    // Lấy các phản hồi của review cha
    // public function replies()
    // {
    //     return $this->hasMany(self::class, 'parent_id');
    // }

    // (Tuỳ chọn) Lấy review cha của phản hồi
    // public function parent()
    // {
    //     return $this->belongsTo(self::class, 'parent_id');
    // }
}
