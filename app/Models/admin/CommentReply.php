<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Comment;
use App\Models\User;  // nếu User nằm trong App\Models

class CommentReply extends Model
{
    protected $fillable = ['comment_id', 'admin_id', 'reply'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
