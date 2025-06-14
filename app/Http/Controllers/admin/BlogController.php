<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Blog::query();

        // Lọc theo ngày bắt đầu hiển thị
        if ($request->has('start_date') && $request->input('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }

        // Lọc theo ngày kết thúc hiển thị
        if ($request->has('end_date') && $request->input('end_date')) {
            $query->whereDate('end_date', '<=', $request->input('end_date'));
        }

        $blog = $query->get();

        return view('backend.blogs.index', compact('blog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // Handle file upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blogs'), $filename);
            $thumbnail = 'uploads/blogs/' . $filename;
        }

        Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'thumbnail' => $thumbnail,
            'start_date' => $request->start_date
                ? Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d')
                : null,
            'end_date' => $request->end_date
                ? Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d')
                : null
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::findOrFail($id);
        return view('backend.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        return view('backend.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
                unlink(public_path($blog->thumbnail));
            }

            // Upload new thumbnail
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blogs'), $filename);
            $blog->thumbnail = 'uploads/blogs/' . $filename;
        }

        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->content = $request->content;
        $blog->start_date = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d')
            : null;

        $blog->end_date = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d')
            : null;
        $blog->save();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    /**
     * Kiểm tra blog có đang hiển thị để chặn xóa mềm.
     */
    protected function isCurrentlyDisplaying(Blog $blog): bool
    {
        return $blog->active && $blog->end_date && now()->lessThanOrEqualTo(Carbon::parse($blog->end_date)->endOfDay());
    }

    /**
     * Xóa mềm một blog (nếu không còn hiển thị).
     */
    public function softDelete(string $id)
    {
        $blog = Blog::findOrFail($id);

        if ($this->isCurrentlyDisplaying($blog)) {
            return redirect()->route('admin.blog.index')->with('error', 'Không thể xóa blog đang trong thời gian hiển thị.');
        }

        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog đã được chuyển vào thùng rác.');
    }

    /**
     * Xóa vĩnh viễn một blog đã bị xóa mềm.
     */
    public function forceDelete(string $id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->forceDelete();
        return redirect()->route('admin.blog.trashed')->with('success', 'Đã xoá vĩnh viễn blog.');
    }

    /**
     * Khôi phục một blog đã bị xóa mềm.
     */
    public function restore(string $id)
    {
        Blog::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.blog.index')->with('success', 'Blog đã được khôi phục.');
    }

    /**
     * Danh sách các blog đã bị xóa mềm.
     */
    public function trashed()
    {
        $blogs = Blog::onlyTrashed()->get();
        return view('backend.blogs.trashed', compact('blogs'));
    }

    /**
     * Xóa mềm nhiều blog cùng lúc.
     */
    public function bulkDelete(Request $request)
    {
        $ids = collect($request->input('ids'))->filter()->values();

        $blogs = Blog::whereIn('id', $ids)->get();

        $deletable = [];
        $blockedTitles = [];

        foreach ($blogs as $blog) {
            if ($this->isCurrentlyDisplaying($blog)) {
                $blockedTitles[] = $blog->title;
            } else {
                $deletable[] = $blog->id;
            }
        }

        Blog::whereIn('id', $deletable)->delete();

        $deletedCount = count($deletable);
        $blockedCount = count($blockedTitles);

        $messages = [];
        if ($deletedCount) {
            $messages[] = "Đã xóa mềm $deletedCount blog.";
        }
        if ($blockedCount) {
            $messages[] = "Không thể xóa $blockedCount blog đang hiển thị: " . implode(', ', $blockedTitles);
        }

        return response()->json([
            'message' => implode(' ', $messages),
            'status' => $deletedCount > 0
                ? ($blockedCount > 0 ? 'warning' : 'success')
                : 'error',
            'deletedCount' => $deletedCount
        ], 200);
    }

    /**
     * Khôi phục nhiều blog đã bị xóa mềm.
     */
    public function bulkRestore(Request $request)
    {
        $ids = collect($request->input('ids'))->filter()->values();
        Blog::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json([
            'message' => 'Đã khôi phục các blog đã chọn thành công.',
            'status' => 'success'
        ], 200);
    }

    /**
     * Xóa vĩnh viễn nhiều blog.
     */
    public function bulkForceDelete(Request $request)
    {
        $ids = collect($request->input('ids'))->filter()->values();

        $deletedCount = 0;

        $blogs = Blog::onlyTrashed()->whereIn('id', $ids)->get();

        foreach ($blogs as $blog) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->forceDelete();
            $deletedCount++;
        }

        return response()->json([
            'message' => "Đã xóa vĩnh viễn $deletedCount blog.",
            'status' => 'success',
            'deletedCount' => $deletedCount
        ], 200);
    }
}
