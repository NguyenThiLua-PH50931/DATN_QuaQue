<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\admin\Product;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $dates = ['deleted_at'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
