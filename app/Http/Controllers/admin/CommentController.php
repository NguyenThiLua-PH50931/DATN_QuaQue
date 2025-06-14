<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Comment;
use App\Models\admin\CommentReply;
use Illuminate\Http\Request;
use App\Mail\CommentReplied;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{


    public function index(Request $request)
    {
        $query = Comment::with(['user', 'product', 'replies']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('content', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $comments = $query->latest()->paginate(10);
        $comments->appends($request->all());

        return view('backend.comments.index', compact('comments'));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được xóa thành công!');
    }

    public function edit($id)
    {
        $comment = Comment::with('replies')->findOrFail($id);
        return view('backend.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:hidden,visible'],
        ]);

        $comment = Comment::findOrFail($id);
        $oldStatus = $comment->status;
        $comment->update(['status' => $request->status]);

        // Gửi email nếu trạng thái thay đổi
        if ($oldStatus !== $request->status) {
            try {
                Mail::to($comment->user->email)->send(new CommentReplied($comment));
                \Log::info('Status update email sent to: ' . $comment->user->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send status update email for comment ' . $id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.comments.index')->with('success', 'Trạng thái bình luận đã được cập nhật thành công!');
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'visible']);
        try {
            Mail::to($comment->user->email)->send(new CommentReplied($comment));
            \Log::info('Approval email sent to: ' . $comment->user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email for comment ' . $id . ': ' . $e->getMessage());
        }
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được hiển thị!');
    }

    public function reject($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'hidden']);
        try {
            Mail::to($comment->user->email)->send(new CommentReplied($comment));
            \Log::info('Rejection email sent to: ' . $comment->user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email for comment ' . $id . ': ' . $e->getMessage());
        }
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã bị ẩn!');
    }

    public function reply($id)
    {
        $comment = Comment::with('replies')->findOrFail($id);
        return view('backend.comments.reply', compact('comment'));
    }

    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $comment = Comment::findOrFail($id);
        CommentReply::create([
            'comment_id' => $id,
           'admin_id' => auth()->id(),
            'admin_id' => auth()->id(),
            'reply' => $request->reply,
        ]);

        try {
            Mail::to($comment->user->email)->send(new CommentReplied($comment, $request->reply));
            \Log::info('Reply email sent to: ' . $comment->user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send reply email for comment ' . $id . ': ' . $e->getMessage());
        }

        return redirect()->route('admin.comments.reply', $id)->with('success', 'Phản hồi đã được gửi!');
    }
    public function editReply($id, $replyId)
    {
        $comment = Comment::with('replies')->findOrFail($id);
        $reply = CommentReply::where('comment_id', $id)->findOrFail($replyId);
        return view('backend.comments.edit_reply', compact('comment', 'reply'));
    }

    public function updateReply(Request $request, $id, $replyId)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $reply = CommentReply::where('comment_id', $id)->findOrFail($replyId);
        $reply->update([
            'reply' => $request->reply,
        ]);

        $comment = Comment::findOrFail($id);
        try {
            Mail::to($comment->user->email)->send(new CommentReplied($comment, $request->reply));
            \Log::info('Updated reply email sent to: ' . $comment->user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send updated reply email for comment ' . $id . ': ' . $e->getMessage());
        }

        return redirect()->route('admin.comments.reply', $id)->with('success', 'Phản hồi đã được cập nhật thành công!');
    }

    public function destroyReply($id, $replyId)
    {
        $reply = CommentReply::where('comment_id', $id)->findOrFail($replyId);
        $reply->delete();
        return redirect()->route('admin.comments.reply', $id)->with('success', 'Phản hồi đã được xóa thành công!');
    }
}
