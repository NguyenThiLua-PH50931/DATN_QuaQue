<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\admin\Blog;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'blog_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
