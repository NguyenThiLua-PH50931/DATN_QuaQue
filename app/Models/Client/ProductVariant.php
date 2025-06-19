<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'name',
        'description',
        'image',
        'price',
        'weight',
        'volume',
        'stock',
        'status',
        'active',
    ];

    // Sản phẩm cha
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Giá trị thuộc tính của biến thể
    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attribute_values',
            'product_variant_id',
            'attribute_value_id'
        )->with('attribute');
    }
    public function images()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id'); // Nếu có bảng này
    }
}
