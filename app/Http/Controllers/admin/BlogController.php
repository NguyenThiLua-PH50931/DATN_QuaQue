<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $data = $request->validate([
        'date_from' => 'nullable|date',
        'date_to'   => 'nullable|date|after_or_equal:date_from',
    ]);

    $query = Blog::query();

    // Lọc theo ngày
    if (!empty($data['date_from'])) {
        $query->whereDate('created_at', '>=', $data['date_from']);
    }

    if (!empty($data['date_to'])) {
        $query->whereDate('created_at', '<=', $data['date_to']);
    }

    $blog = $query->latest()
        ->paginate(10)
        ->appends($request->only(['date_from', 'date_to'])); // Giữ lại dữ liệu lọc trên phân trang

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
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
            'thumbnail' => $thumbnail
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
