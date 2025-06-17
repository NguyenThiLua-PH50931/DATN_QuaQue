<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use SoftDeletes;

    protected $table = 'attribute_values';

    protected $fillable = ['attribute_id', 'value', 'slug'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class)->withTrashed();
    }

    public function variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_attribute_values',
            'attribute_value_id',
            'product_variant_id'
        )->withPivot('attribute_id')->withTrashed();
    }
}
