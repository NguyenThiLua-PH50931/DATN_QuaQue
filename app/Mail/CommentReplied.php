<?php

namespace App\Mail;

use App\Models\admin\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentReplied extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    public $reply;

    public function __construct(Comment $comment, $reply = null)
    {
        $this->comment = $comment;
        $this->reply = $reply;
    }

    public function build()
    {
        return $this->subject('Phản hồi bình luận #' . $this->comment->id)
                    ->view('emails.comment_replied');
    }
}
