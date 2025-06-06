<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Trang quản trị: hiển thị danh sách categories (có phân trang)
    public function index(Request $request)
    {
        $categories = Category::paginate(10);

        if ($request->ajax()) {
            return response()->json(['categories' => $categories], 200);
        }

        return view('backend.categories.index', compact('categories'));
    }

    // Trang frontend: hiển thị tất cả categories (không phân trang)
    public function showCategories()
    {
        $categories = Category::all();
        return view('frontend.categories.index', compact('categories'));
    }

    // Trang quản trị: form tạo mới category
    public function create()
    {
        return view('backend.categories.create');
    }

    // Trang quản trị: form chỉnh sửa category
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }

    // Lưu category mới (store)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        session()->flash('success', 'Thêm danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    // Cập nhật category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        session()->flash('success', 'Cập nhật danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    // Xóa mềm (soft delete)
    public function softDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        session()->flash('success', 'Xóa danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    // Xóa cứng (force delete)
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->forceDelete();

        return response()->json(['message' => 'Xóa vĩnh viễn danh mục thành công'], 200);
    }

    // Khôi phục soft deleted
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        session()->flash('success', 'Khôi phục danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    // Danh sách các danh mục đã bị soft deleted
    public function trashed()
    {
        $categories = Category::onlyTrashed()->get();
        return view('backend.categories.trashed', compact('categories'));
    }

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
