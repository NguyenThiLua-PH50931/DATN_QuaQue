<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\BlogComment;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'content' => 'required|string|max:1000',
        ]);

        BlogComment::create([
            'user_id' => Auth::id(),
            'blog_id' => $request->blog_id,
            'content' => $request->content,
        ]);

        return redirect()->to(url()->previous() . '#comments')->with('success', 'Bình luận đã được gửi.');
    }
}
