<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Category;
use App\Models\admin\Product;
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

        // Kiểm tra xem danh mục có liên kết với bất kỳ sản phẩm nào không
        if (Product::where('category_id', $id)->exists()) {
            session()->flash('error', 'Không thể xóa danh mục này vì đang có sản phẩm liên kết.');
            return redirect()->route('admin.categories.index');
        }

        $category->delete();

        session()->flash('success', 'Xóa danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    // Xóa cứng (force delete)
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        // Kiểm tra xem danh mục có liên kết với bất kỳ sản phẩm nào không
        // Nếu một danh mục đã bị xóa mềm nhưng vẫn còn liên kết với sản phẩm, chúng ta vẫn không cho phép xóa cứng.
        if (Product::where('category_id', $id)->exists()) {
            return response()->json(['message' => 'Không thể xóa vĩnh viễn danh mục này vì đang có sản phẩm liên kết.', 'status' => 'error'], 400);
        }

        $category->forceDelete();

        return response()->json(['message' => 'Xóa vĩnh viễn danh mục thành công', 'status' => 'success'], 200);
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        $deletedCount = 0;
        $notDeletedNames = [];

        foreach ($ids as $id) {
            $category = Category::find($id);
            if ($category) {
                if (Product::where('category_id', $id)->exists()) {
                    $notDeletedNames[] = $category->name; // Lưu tên danh mục không thể xóa
                } else {
                    $category->delete(); // Xóa mềm danh mục
                    $deletedCount++;
                }
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= 'Đã xóa mềm ' . $deletedCount . ' danh mục thành công.';
        }

        if (count($notDeletedNames) > 0) {
            if ($deletedCount > 0) {
                $message .= ' Tuy nhiên, ';
            }
            $message .= 'các danh mục sau không thể xóa do có sản phẩm liên kết: ' . implode(', ', $notDeletedNames) . '.';
            return response()->json(['message' => $message, 'status' => 'warning', 'deletedCount' => $deletedCount, 'notDeletedNames' => $notDeletedNames], 200);
        }

        return response()->json(['message' => $message, 'status' => 'success', 'deletedCount' => $deletedCount], 200);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        Category::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['message' => 'Đã khôi phục các danh mục đã chọn thành công.'], 200);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));

        // Kiểm tra từng danh mục xem có sản phẩm liên kết không (ngay cả khi đã xóa mềm)
        foreach ($ids as $id) {
            // Tìm danh mục bao gồm cả những cái đã bị xóa mềm
            $category = Category::withTrashed()->find($id);
            if ($category && Product::where('category_id', $id)->exists()) {
                return response()->json(['message' => 'Không thể xóa vĩnh viễn một hoặc nhiều danh mục đã chọn vì đang có sản phẩm liên kết.', 'status' => 'error'], 400);
            }
        }

        Category::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['message' => 'Đã xóa vĩnh viễn các danh mục đã chọn thành công.', 'status' => 'success'], 200);
    }

    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm danh mục mới!');
    }
}
