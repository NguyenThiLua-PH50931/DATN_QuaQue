<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Comment;
use App\Models\admin\CommentReply;
use Illuminate\Http\Request;
use App\Mail\CommentReplied;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Display a listing of comments.
     */
    public function index(Request $request)
    {
        // Eager load user, product and replies' authors to avoid N+1
        $query = Comment::with(['user', 'product', 'replies.user', 'replies.admin']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
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

    /**
     * Remove the specified comment.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        try {
            $comment->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete comment {$id}: " . $e->getMessage());
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Xóa bình luận thất bại.'], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Xóa bình luận thất bại.']);
        }

        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json(['success' => true, 'message' => 'Bình luận đã được xóa thành công!']);
        }

        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được xóa thành công!');
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit($id)
    {
        $comment = Comment::with(['replies.user', 'replies.admin', 'user', 'product'])->findOrFail($id);
        return view('backend.comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment's status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:hidden,visible'],
        ]);

        $comment = Comment::findOrFail($id);
        $oldStatus = $comment->status;

        try {
            $comment->update(['status' => $request->status]);
        } catch (\Exception $e) {
            Log::error("Failed to update comment {$id} status: " . $e->getMessage());
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Cập nhật trạng thái thất bại.'], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Cập nhật trạng thái thất bại.']);
        }

        // Gửi email khi trạng thái thay đổi (nếu user tồn tại và có email)
        if ($oldStatus !== $request->status) {
            try {
                if ($comment->user && !empty($comment->user->email)) {
                    Mail::to($comment->user->email)->send(new CommentReplied($comment));
                    Log::info('Status update email sent to: ' . $comment->user->email);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send status update email for comment ' . $id . ': ' . $e->getMessage());
            }
        }

        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Trạng thái bình luận đã được cập nhật thành công!',
                'status' => $request->status
            ]);
        }

        return redirect()->route('admin.comments.index')->with('success', 'Trạng thái bình luận đã được cập nhật thành công!');
    }

    /**
     * Approve (make visible) the comment.
     */
    public function approve($id)
    {
        $comment = Comment::findOrFail($id);

        try {
            $comment->update(['status' => 'visible']);
        } catch (\Exception $e) {
            Log::error("Failed to approve comment {$id}: " . $e->getMessage());
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Phê duyệt thất bại.'], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Phê duyệt thất bại.']);
        }

        // send email if user exists & has email
        try {
            if ($comment->user && !empty($comment->user->email)) {
                Mail::to($comment->user->email)->send(new CommentReplied($comment));
                Log::info('Approval email sent to: ' . $comment->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send approval email for comment ' . $id . ': ' . $e->getMessage());
        }

        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json(['success' => true, 'message' => 'Bình luận đã được hiển thị!']);
        }

        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được hiển thị!');
    }

    /**
     * Reject (hide) the comment.
     */
    public function reject($id)
    {
        $comment = Comment::findOrFail($id);

        try {
            $comment->update(['status' => 'hidden']);
        } catch (\Exception $e) {
            Log::error("Failed to hide comment {$id}: " . $e->getMessage());
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Ẩn bình luận thất bại.'], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Ẩn bình luận thất bại.']);
        }

        try {
            if ($comment->user && !empty($comment->user->email)) {
                Mail::to($comment->user->email)->send(new CommentReplied($comment));
                Log::info('Rejection email sent to: ' . $comment->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email for comment ' . $id . ': ' . $e->getMessage());
        }

        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json(['success' => true, 'message' => 'Bình luận đã bị ẩn!']);
        }

        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã bị ẩn!');
    }

    /**
     * Show reply form and replies for a comment.
     */
    public function reply($id)
    {
        // load comment + replies + authors to avoid N+1 queries
        $comment = Comment::with(['replies.user', 'replies.admin', 'user', 'product'])->findOrFail($id);

        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json($comment);
        }

        return view('backend.comments.reply', compact('comment'));
    }

    /**
     * Store a reply to a comment.
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $comment = Comment::with('user')->findOrFail($id);

        $payload = [
            'comment_id' => $id,
            'reply' => $request->input('reply'),
        ];

        // safe check: if admin guard exists and admin is logged in, set admin_id
        $guards = config('auth.guards') ?: [];
        if (is_array($guards) && array_key_exists('admin', $guards) && auth()->guard('admin')->check()) {
            $payload['admin_id'] = auth()->guard('admin')->id();
        } elseif (auth()->check()) {
            // fallback: normal logged-in user
            $payload['user_id'] = auth()->id();
        } else {
            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để trả lời.'], 401);
            }
            return redirect()->back()->withErrors(['Bạn cần đăng nhập để trả lời.']);
        }

        try {
            $reply = CommentReply::create($payload);
        } catch (\Exception $e) {
            Log::error('Failed to create comment reply: ' . $e->getMessage(), ['payload' => $payload, 'comment_id' => $id]);

            if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Tạo phản hồi thất bại.'], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Tạo phản hồi thất bại. Vui lòng thử lại.']);
        }

        // Send mail to comment owner if exists & has email
        try {
            if ($comment->user && !empty($comment->user->email)) {
                Mail::to($comment->user->email)->send(new CommentReplied($comment, $request->input('reply')));
                Log::info('Reply email sent to: ' . $comment->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send reply email for comment ' . $id . ': ' . $e->getMessage());
        }

        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Phản hồi đã được gửi!',
                'reply' => $reply,
            ]);
        }

        return redirect()->route('admin.comments.reply', $id)->with('success', 'Phản hồi đã được gửi!');
    }

    /**
     * Show edit form for a reply.
     */
    public function editReply($id, $replyId)
    {
        $comment = Comment::with(['replies.user', 'replies.admin', 'user'])->findOrFail($id);
        $reply = CommentReply::where('comment_id', $id)->findOrFail($replyId);

        return view('backend.comments.edit_reply', compact('comment', 'reply'));
    }

    /**
     * Update a reply.
     */
    public function updateReply(Request $request, $id, $replyId)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $reply = CommentReply::where('comment_id', $id)->findOrFail($replyId);

        try {
            $reply->update([
                'reply' => $request->input('reply'),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update reply {$replyId} for comment {$id}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Cập nhật phản hồi thất bại.']);
        }

        // send notification email to comment owner if exists
        try {
            $comment = Comment::findOrFail($id);
            if ($comment->user && !empty($comment->user->email)) {
                Mail::to($comment->user->email)->send(new CommentReplied($comment, $request->input('reply')));
                Log::info('Updated reply email sent to: ' . $comment->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send updated reply email for comment ' . $id . ': ' . $e->getMessage());
        }

        return redirect()->route('admin.comments.reply', $id)->with('success', 'Phản hồi đã được cập nhật thành công!');
    }

    /**
     * Delete a reply.
     */
    public function destroyReply($id, $replyId)
    {
        $reply = CommentReply::where('comment_id', $id)->findOrFail($replyId);

        try {
            $reply->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete reply {$replyId} for comment {$id}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Xóa phản hồi thất bại.']);
        }

        return redirect()->route('admin.comments.reply', $id)->with('success', 'Phản hồi đã được xóa thành công!');
    }
}
