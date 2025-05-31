<?php

namespace App\Http\Controllers;

use App\Models\admin\Category;

class CategoryController extends Controller
{
    // Hiển thị danh sách các danh mục
    public function showCategories()
    {
        // Lấy tất cả các danh mục từ cơ sở dữ liệu
        $categories = Category::all();
        
        // Trả về view với biến categories
        return view('frontend.categories.index', compact('categories'));
    }
}
