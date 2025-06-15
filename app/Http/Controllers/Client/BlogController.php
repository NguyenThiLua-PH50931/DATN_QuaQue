<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\admin\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog.
     */
    public function index(Request $request)
    {
        $blog = Blog::query()->orderBy('id', 'desc')->get();
        $recentBlogs = Blog::query()
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
