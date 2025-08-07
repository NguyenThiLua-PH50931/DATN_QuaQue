<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'region_id',
        'name',
        'slug',
        'description',
        'image',
        'origin',
        'price',
        'active',
        'view_total',
        'view_day',
        'view_week',
        'view_month',
        'has_variants',
    ];

    // Ảnh nhiều
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    // Biến thể (variants)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    // Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Vùng miền
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    // Review
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    // Bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }
}
