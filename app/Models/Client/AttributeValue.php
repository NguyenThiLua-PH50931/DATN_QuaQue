<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use SoftDeletes;

    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
    ];

    // Liên kết về Attribute
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    // Nhiều biến thể có thể có giá trị này
    public function productVariants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_attribute_values',
            'attribute_value_id',
            'product_variant_id'
        );
    }
}
