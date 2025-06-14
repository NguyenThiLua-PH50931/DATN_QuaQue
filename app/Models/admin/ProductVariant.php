<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'description',
        'price',
        'weight',
        'volume',
        'stock',
        'barcode',
        'status',
        'active',
        'image',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attribute_values',
            'product_variant_id',
            'attribute_value_id'
        )->withPivot('attribute_id');
    }
}
