<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->back()->with('success', 'Đã thêm danh mục mới!');
    }
}
