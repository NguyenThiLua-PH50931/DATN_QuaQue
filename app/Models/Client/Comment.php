<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Nếu có phản hồi admin cho comment:
    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }
}
