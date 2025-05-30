<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    // Quan hệ với bảng products (nếu có)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
