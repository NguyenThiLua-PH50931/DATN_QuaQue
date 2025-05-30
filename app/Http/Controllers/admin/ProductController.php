<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product;  // Đường dẫn model Product trong thư mục Models/Admin

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm trong trang admin.
     */
    public function index()
{
    $products = Product::with('category')->orderBy('created_at', 'desc')->get();
    dd($products);  // Tạm thời debug
    return view('backend.products.index', compact('products'));
}


}
