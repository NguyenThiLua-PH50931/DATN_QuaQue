<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function detail($slug)
    {
        // Lấy sản phẩm theo slug, kèm category, region, seller (user), images, reviews
        $product = Product::with([
            'category',
            'region',
            'seller',
            'images',
            'reviews.user' // nếu muốn show tên người review
        ])->where('slug', $slug)->firstOrFail();

        $topProducts = Product::with(['category', 'reviews'])
            ->orderByDesc('view_week')
            ->limit(4)
            ->get();

        // Tăng view
        $product->increment('view_total');
        $product->increment('view_day');
        $product->increment('view_week');
        $product->increment('view_month');
        // Lấy 4 sản phẩm cùng danh mục, trừ sản phẩm hiện tại
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '<>', $product->id)
            ->latest()->limit(4)->get();

        return view('frontend.products.detail', compact('product', 'relatedProducts', 'topProducts'));
    }
}
