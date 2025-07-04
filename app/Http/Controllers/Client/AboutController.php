<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Blog; // Assuming you have a Blog model in the admin namespace
use App\Models\admin\BlogComment;

class AboutController extends Controller
{

public function index()
{
    // 5 bài blog mới nhất
    $blog = Blog::latest()->take(5)->get();

    // 3 bài viết gần đây
    $recentBlogs = Blog::latest()->take(3)->get();

    // 6 bình luận blog mới nhất có user và blog
    $recentComments = BlogComment::with(['user', 'blog'])->latest()->take(6)->get();

    return view('frontend.about.about', compact('blog', 'recentBlogs', 'recentComments'));
}

}
