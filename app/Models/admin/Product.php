<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\admin\Category;
use App\Models\admin\Region;
use App\Models\admin\Variant;
use App\Models\admin\Review;
use App\Models\admin\Comment;
use App\Models\admin\ProductImage;

class Product extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'category_id',
        'region_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'origin',
        'view_total',
        'view_day',
        'view_week',
        'view_month',
        'created_at',
        'updated_at'
    ];

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
        return $this->hasMany(Variant::class, 'product_id', 'id');
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
    public function coupons()
    {
        return $this->belongsToMany(Coupons::class, 'coupon_product', 'product_id', 'coupon_id');
    }
}
