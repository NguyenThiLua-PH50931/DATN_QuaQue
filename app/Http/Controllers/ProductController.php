<?php
namespace App\Http\Controllers;

use App\Models\BE\Product;
use App\Models\BE\Category; // thêm use Category

class ProductController extends Controller
{
    public function home()
    {
        $topViewedProducts = Product::orderBy('view_week', 'desc')->take(8)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')->take(12)->get();

        $categories = Category::all();  // <-- Lấy danh mục

        return view('frontend.home', compact('topViewedProducts', 'latestProducts', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $product->increment('view_total');
        $product->increment('view_day');
        $product->increment('view_week');
        $product->increment('view_month');

        return view('frontend.products.details', compact('product'));
    }
}
