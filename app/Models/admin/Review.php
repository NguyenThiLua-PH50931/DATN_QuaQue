<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product; // ✅ Thêm dòng này nếu cần
use App\Models\User;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rating', 'comment', 'parent_id']; // ✅ Thêm 'parent_id' nếu dùng

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Lấy các phản hồi của review cha
    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // (Tuỳ chọn) Lấy review cha của phản hồi
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
