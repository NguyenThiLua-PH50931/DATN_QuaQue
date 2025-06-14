<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'slug'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    // Nếu có quan hệ đến sản phẩm thì giữ, không thì bỏ
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
        public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
