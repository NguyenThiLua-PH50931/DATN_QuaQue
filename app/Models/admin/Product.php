<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\admin\Category;
use App\Models\admin\Region;
use App\Models\admin\ProductVariant;
use App\Models\admin\Review;
use App\Models\admin\Comment;
use App\Models\admin\ProductImage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'seller_id',
        'category_id',
        'region_id',
        'name',
        'slug',
        'description',
        'short_desc',
        'image',
        'origin',
        'view_total',
        'view_day',
        'view_week',
        'view_month',
        'active',
        'created_at',
        'updated_at',
        'has_variants',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
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

    // Accessors & Mutators
    public function getStatusTextAttribute()
    {
        return $this->active ? 'Đang bán' : 'Ngừng bán';
    }

    public function getStatusClassAttribute()
    {
        return $this->active ? 'success' : 'danger';
    }

    public function getMainImageAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }

    public function getFirstVariantAttribute()
    {
        return $this->variants()->active()->first();
    }

    public function getPriceAttribute()
    {
        $variant = $this->first_variant;
        return $variant ? $variant->price : 0;
    }

    public function getStockAttribute()
    {
        $variant = $this->first_variant;
        return $variant ? $variant->stock : 0;
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->inRandomOrder();
    }
}
