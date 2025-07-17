<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // use SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'status',
    ];

    // 1 comment thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // 1 comment có nhiều reply
    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }
}
