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
// ở phần use:
use App\Models\admin\Order;
use App\Models\admin\OrderItem;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
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
        'deleted_at',
        'has_variants',
    ];

    // Relationships
    // trong model AdminProduct.php
    /**
 * Quan hệ tới order_items (bảng chứa product_id trong mỗi order)
 * Nếu bạn dùng tên model/namespace khác, thay OrderItem::class tương ứng.
 */
public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'product_id', 'id');
}

/**
 * Lấy tất cả orders liên quan thông qua order_items
 */
public function orders()
{
    return $this->hasManyThrough(
        Order::class,        // model đích
        OrderItem::class,    // model trung gian
        'product_id',        // FK trên order_items tới products
        'id',                // PK trên orders
        'id',                // local key trên products
        'order_id'           // FK trên order_items tới orders
    );
}

/**
 * Trạng thái đơn hàng được xem là "đã kết thúc" -> cho phép xóa product nếu
 * tất cả các đơn hàng chứa product đều ở trạng thái kết thúc.
 */
public static function finalOrderStatuses(): array
{
    return ['cancelled', 'delivered', 'failed_delivery'];
}

/**
 * Các trạng thái xem là "chưa hoàn tất" và sẽ NGĂN không cho xóa sản phẩm
 */
public static function activeOrderStatuses(): array
{
    return ['pending', 'confirmed', 'processing', 'shipped', 'in_transit'];
}

/**
 * Kiểm tra product có nằm trong bất kỳ đơn hàng "chưa hoàn tất" nào hay không
 * trả về true nếu có ít nhất 1 đơn hàng chưa hoàn tất chứa product này
 */
public function hasActiveOrder(): bool
{
    return $this->orders()
        ->whereIn('status', self::activeOrderStatuses())
        ->exists();
}

/**
 * Kiểm tra product có thể xóa (soft/force) hay không
 * - true nếu KHÔNG có đơn hàng "chưa hoàn tất"
 * - false nếu còn ít nhất 1 đơn hàng "chưa hoàn tất"
 */
public function canBeDeleted(): bool
{
    return !$this->hasActiveOrder();
}

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
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


    // Quan hệ với Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Sự kiện xóa sản phẩm
    protected static function booted()
    {
        static::deleting(function ($product) {
            // Xóa tất cả bình luận liên quan
            foreach ($product->comments as $comment) {
                // Xóa các phản hồi của bình luận
                $comment->replies()->delete();
                // Xóa bình luận
                $comment->delete();
            }
        });
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
    public function attribute_values()
    {
        return $this->hasManyThrough(AttributeValue::class, ProductVariant::class, 'product_id', 'id', 'id', 'attribute_value_id');
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
