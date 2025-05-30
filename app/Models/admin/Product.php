<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Nếu bạn dùng timestamps (created_at, updated_at) thì giữ nguyên
    public $timestamps = true;

    // Cho phép ghi dữ liệu bằng create() hoặc fill()
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

}
