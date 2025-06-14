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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        
        // Delete thumbnail file
        if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
            unlink(public_path($blog->thumbnail));
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }
}
