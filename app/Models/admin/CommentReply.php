<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Comment;
use App\Models\User;

class CommentReply extends Model
{
    protected $table = 'comment_replies';

    // Cho phép mass assign cả user_id và admin_id
    protected $fillable = [
        'comment_id',
        'user_id',
        'admin_id',
        'reply',
    ];

    // Nếu bạn muốn tự động cast created_at/updated_at, Laravel đã làm sẵn

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    // Người trả lời nếu là user thường
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Người trả lời nếu là admin (ở đây admin cũng là record trong users table)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
        // Nếu bạn có model App\Models\Admin thì đổi thành:
        // return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }
}
