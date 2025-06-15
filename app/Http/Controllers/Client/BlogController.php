<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\admin\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Lấy tất cả blog hợp lệ (đang trong thời gian hiển thị)
        $blog = Blog::query()
            ->whereDate('start_date', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $now);
            })
            ->orderBy('id', 'desc')
            ->get();

        // Lấy 5 blog gần đây nhất cũng trong thời gian hợp lệ
        $recentBlogs = Blog::query()
            ->whereDate('start_date', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $now);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('frontend.blogs.blogGrid', compact('blog', 'recentBlogs'));
    }

    /**
     * Display the specified blog.
     */
    public function show(string $id)
    {
        $blog = Blog::findOrFail($id);
        $recentBlogs = Blog::query()
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        return view('frontend.blogs.blogDetail', compact('blog', 'recentBlogs'));
    }
}
