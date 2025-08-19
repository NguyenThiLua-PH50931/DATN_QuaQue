<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Category;
use App\Models\admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Trang quản trị: hiển thị danh sách categories (có phân trang)
    public function index(Request $request)
    {
        $categories = Category::all();

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
            'name' => 'required|string|max:100|unique:categories,name',
            'image' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'image.image' => 'Tệp tải lên phải là ảnh hợp lệ.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only('name');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        session()->flash('success', 'Thêm danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }

    // Cập nhật category (update)
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
            'image' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'image.image' => 'Tệp tải lên phải là ảnh hợp lệ.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only('name');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        session()->flash('success', 'Cập nhật danh mục thành công!');

        return redirect()->route('admin.categories.index');
    }
    // Xóa mềm (soft delete)
    public function softDelete($id)
    {
        $category = Category::findOrFail($id);

        // Kiểm tra xem danh mục có liên kết với bất kỳ sản phẩm nào không (bảng pivot)
        if (DB::table('product_category')->where('category_id', $id)->exists()) {
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

        if (DB::table('product_category')->where('category_id', $id)->exists()) {
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
            $ids = explode(',', $ids);
        }

        $deletedCount = 0;
        $notDeletedNames = [];

        foreach ($ids as $id) {
            $category = Category::find($id);
            if ($category) {
                if (DB::table('product_category')->where('category_id', $id)->exists()) {
                    $notDeletedNames[] = $category->name;
                } else {
                    $category->delete();
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

        foreach ($ids as $id) {
            $category = Category::withTrashed()->find($id);
            if ($category && DB::table('product_category')->where('category_id', $id)->exists()) {
                return response()->json(['message' => 'Không thể xóa vĩnh viễn một hoặc nhiều danh mục đã chọn vì đang có sản phẩm liên kết.', 'status' => 'error'], 400);
            }
        }

        Category::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['message' => 'Đã xóa vĩnh viễn các danh mục đã chọn thành công.', 'status' => 'success'], 200);
    }


    public function storeQuick(Request $request)
    {

        // Validator (thêm image)
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:100|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // <= 2MB
        ], [
            'name.required' => 'Tên danh mục bắt buộc',
            'name.unique'   => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác',
            'name.max'      => 'Tên danh mục không được vượt quá 100 ký tự',
            'image.image'   => 'Tệp tải lên phải là ảnh hợp lệ.',
            'image.mimes'   => 'Ảnh phải có định dạng: jpeg,png,jpg,gif,svg.',
            'image.max'     => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        // Nếu lỗi validate, trả về JSON lỗi (dạng object field => [messages])
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(), // Illuminate\Support\MessageBag -> JSON-friendly

                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Xử lý file (nếu có)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public'); // lưu vào storage/app/public/categories/...
        }

        // Tạo danh mục
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath, // lưu path tương đối (null nếu không có)
        ]);

        // Trả về JSON (có thêm url ảnh để client dễ dùng)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Đã thêm danh mục mới!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image, // path lưu trong storage
                    'image_url' => $category->image ? asset('storage/' . $category->image) : null,
                ],
            ], 201);
        }
        return redirect()->back()->with('success', 'Đã thêm danh mục mới!');
    }
}
