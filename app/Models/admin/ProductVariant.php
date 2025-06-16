<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

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
        'image',
        'active',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // Accessors & Mutators
    public function getStatusTextAttribute()
    {
        return $this->active ? 'Đang bán' : 'Ngừng bán';
    }

    public function getStatusClassAttribute()
    {
        return $this->active ? 'success' : 'danger';
    }

    public function getStockStatusAttribute()
    {
        return $this->stock > 0 ? 'Còn hàng' : 'Hết hàng';
    }

    public function getStockClassAttribute()
    {
        return $this->stock > 0 ? 'success' : 'danger';
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price) . 'đ';
    }
}
