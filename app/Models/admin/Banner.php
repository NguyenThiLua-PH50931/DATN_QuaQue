<?php

namespace App\Models\Admin;


use App\Models\admin\Traits\BannerTimeValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes, BannerTimeValidation;


    /**
     * Mapping of banner location keys to their Vietnamese labels.
     */
    public const LOCATION_LABELS = [
        'main_hero_banner' => 'Banner Chính Đầu Trang',
        'small_promo_banner_top' => 'Banner Đầu Trang Nhỏ Bên Phải (Trên)',
        'small_promo_banner_bottom' => 'Banner Đầu Trang Nhỏ Bên Phải (Dưới)',
        'slider_banner' => 'Banner Trượt (Slider)',
        'product_section_promo_left_top' => 'Banner Sản Phẩm Dọc - Trên',
        'product_section_promo_left_bottom' => 'Banner Sản Phẩm Dọc - Dưới',
        'category_section_promo_left' => 'Banner Sản Phẩm Theo Danh Mục - Trái',
        'category_section_promo_right' => 'Banner Sản Phẩm Theo Danh Mục - Phải',
        'new_products_cashback_banner' => 'Banner Sản Phẩm Mới',
        'new_products_promo_left' => 'Banner Sản Phẩm Mới (Trái)',
        'new_products_promo_right' => 'Banner Sản Phẩm Mới (Phải)',
        'last_page_promo_banner' => 'Banner Cuối Trang',
    ];

    protected $fillable = [
        'title',
        'image',
        'link',
        'active',
        'display_at',
        'display_end_at',
        'location',
    ];

    protected $dates = [
        'deleted_at',
        'display_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'display_at' => 'datetime',
        'display_end_at' => 'datetime',
        'active' => 'boolean',
    ];


    /**
     * Accessor to get the Vietnamese label for the banner location.
     */
    public function getLocationLabelAttribute()
    {
        if (!$this->location) {
            return 'N/A';
        }

        return self::LOCATION_LABELS[$this->location] ?? $this->location;
    }
}
