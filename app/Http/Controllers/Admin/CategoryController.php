<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BE\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $categories = Category::paginate(10);

        if ($request->ajax()) {
            return response()->json(['categories' => $categories], 200);
        }

        return view('backend.categories.index', compact('categories'));
    }

    /**
     * Display a listing of soft-deleted categories.
     */
    public function trashed()
    {
        $categories = Category::onlyTrashed()->get();
        return view('backend.categories.trashed', compact('categories'));
    }

    public function create()
    {
        return view('backend.categories.create');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }


    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        session()->flash('success', 'Thêm danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    /**
     * Update the specified category in storage.
     */
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

        // Flash a success message to the session
        session()->flash('success', 'Cập nhật danh mục thành công!');

        // Redirect to the categories index page
        return redirect()->route('admin.categories.index');
    }

    /**
     * Soft delete the specified category.
     */
    public function softDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        // Flash a success message to the session
        session()->flash('success', 'Xóa mềm danh mục thành công!');

        // Redirect to the categories index page
        return redirect()->route('admin.categories.index');
    }

    /**
     * Hard delete the specified category.
     */
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->forceDelete();

        return response()->json(['message' => 'Xóa cứng danh mục thành công'], 200);
    }

    /**
     * Restore the soft-deleted category.
     */
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        // Flash a success message to the session
        session()->flash('success', 'Khôi phục danh mục thành công!');

        // Redirect to the categories index page
        return redirect()->route('admin.categories.index');
    }
}
