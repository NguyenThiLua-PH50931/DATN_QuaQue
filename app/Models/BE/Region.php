<?php

namespace App\Models\BE;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    // Quan hệ với bảng products nếu có
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
