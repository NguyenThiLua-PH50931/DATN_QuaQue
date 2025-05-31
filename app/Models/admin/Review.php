<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rating', 'comment'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function replies()
    {
        return $this->hasMany(\App\Models\admin\Review::class, 'parent_id');
    }
}
