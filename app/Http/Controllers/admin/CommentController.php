<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Comment;
use App\Models\admin\CommentReply;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   

   public function index(Request $request)
{
    $query = Comment::with(['user', 'product']);

    // Tìm kiếm theo nội dung, người dùng hoặc sản phẩm
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

    // Lọc theo trạng thái
    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    // Lọc theo thời gian
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->input('date'));
    }

    $comments = $query->latest()->paginate(10);
    $comments->appends($request->all()); // Giữ query string khi phân trang

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
        $comment = Comment::findOrFail($id);
        return view('backend.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update(['content' => $request->content]);

        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được cập nhật thành công!');
    }

    // Duyệt bình luận
    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'approved']);
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được duyệt!');
    }

    // Từ chối bình luận
    public function reject($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'rejected']);
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã bị từ chối!');
    }
    // tra loi binh luan
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $comment = Comment::findOrFail($id);
        CommentReply::create([
            'comment_id' => $id,
            'admin_id' => auth()->id(),


            'reply' => $request->reply,
        ]);

        return redirect()->route('admin.comments.edit', $id)->with('success', 'Phản hồi đã được gửi!');
    }
}
