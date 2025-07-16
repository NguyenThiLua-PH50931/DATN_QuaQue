<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    protected $table = 'comment_replies';

    protected $fillable = [
        'comment_id',
        'user_id',
        'reply',
    ];

    // 1 reply thuộc về 1 comment
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    // 1 reply thuộc về 1 user (ai trả lời)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
