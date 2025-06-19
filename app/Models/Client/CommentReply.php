<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    protected $table = 'comment_replies';

    protected $fillable = [
        'comment_id',
        'admin_id',
        'reply',
    ];

    // Comment gốc
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    // Admin trả lời
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }
}
