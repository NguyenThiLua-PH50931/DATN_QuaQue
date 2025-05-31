<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;             // User ở thư mục App\Models (nếu vậy)
use App\Models\admin\Product;    // Product trong thư mục admin
use App\Models\admin\CommentReply;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'content', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }
}
