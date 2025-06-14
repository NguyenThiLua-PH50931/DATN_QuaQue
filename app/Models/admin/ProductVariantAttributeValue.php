<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariantAttributeValue extends Model
{
    protected $table = 'product_variant_attribute_values';

    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'attribute_value_id',
    ];

    // Quan hệ tới Variant (product_variant)
    public function variant()
    {
        return $this->belongsTo(Variant::class, 'product_variant_id');
    }

    // Quan hệ tới Attribute
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    // Quan hệ tới AttributeValue
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
