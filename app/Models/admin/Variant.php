<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{

        protected $table = 'product_variants'; 
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'name',
        'description',
        'image',
        'active',
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
