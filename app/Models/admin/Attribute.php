<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id')->withTrashed();
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

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function($attribute) {
            if ($attribute->isForceDeleting()) {
                $attribute->values()->forceDelete();
            } else {
                $attribute->values()->delete();
            }
        });
    }
}
