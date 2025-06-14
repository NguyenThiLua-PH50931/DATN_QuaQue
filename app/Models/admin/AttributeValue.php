<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $table = 'attribute_values';

    protected $fillable = ['attribute_id', 'value', 'slug'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variants()
    {
        return $this->belongsToMany(
            Variant::class,
            'product_variant_attribute_values',
            'attribute_value_id',
            'product_variant_id'
        )->withPivot('attribute_id');
    }
}
