<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attribute;
use App\Models\admin\AttributeValue;
use App\Models\admin\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AttributeController extends Controller
{
    public function storeQuick(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:100|unique:attributes,name',
            'values' => 'required|string',
        ], [
            'name.required'   => 'Tên thuộc tính bắt buộc',
            'name.unique'     => 'Tên thuộc tính đã tồn tại, vui lòng chọn tên khác',
            'values.required' => 'Nhập giá trị thuộc tính (phân tách bởi dấu phẩy)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $attr = Attribute::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // Tách giá trị và lưu (tránh giá trị rỗng)
        $values = array_filter(array_map('trim', explode(',', $request->values)), fn($v) => $v !== '');
        $attributeValues = [];
        foreach ($values as $val) {
            $attributeValues[] = AttributeValue::create([
                'attribute_id' => $attr->id,
                'value'        => $val,
                'slug'         => Str::slug($val),
            ]);
        }

        return response()->json([
            'success'         => true,
            'attribute'       => $attr,
            'attributeValues' => $attributeValues,
            'message'         => 'Thêm thuộc tính nhanh thành công!',
        ]);
    }

    public function index()
    {
        // Lấy attributes kèm giá trị liên quan (giả sử quan hệ 'values' đã khai báo)
        $attributes = Attribute::with('values')->get();

        // Truyền sang view
        return view('backend.attributes.index', compact('attributes'));
    }

    // Form tạo mới thuộc tính
    public function create()
    {
        return view('backend.attributes.create');
    }

    // Lưu thuộc tính mới
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|unique:attributes,name',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|distinct',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
            'values.required' => 'Bạn phải nhập ít nhất một giá trị thuộc tính.',
            'values.array' => 'Giá trị thuộc tính không hợp lệ.',
            'values.*.required' => 'Mỗi giá trị thuộc tính không được để trống.',
            'values.*.distinct' => 'Các giá trị thuộc tính không được trùng nhau.',
        ]);


        // Tạo attribute mới
        $attribute = new Attribute();
        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->save();

        // Lưu các giá trị thuộc tính
        foreach ($request->values as $value) {
            $value = trim($value);
            if ($value) {
                $attribute->values()->create([
                    'value' => $value,
                    'slug' => Str::slug($value),
                ]);
            }
        }


        return redirect()->route('admin.attributes.index')->with('success', 'Thêm thuộc tính thành công!');
    }


    // Hiển thị chi tiết thuộc tính (nếu cần)
    public function show($slug)
    {
        $attribute = Attribute::where('slug', $slug)->with('values')->firstOrFail();

        return view('backend.attributes.show', compact('attribute'));
    }

    // Form chỉnh sửa thuộc tính
    public function edit($slug)
    {
        $attribute = Attribute::where('slug', $slug)->firstOrFail();
        $values = $attribute->values()->pluck('value')->toArray();
        return view('backend.attributes.edit', compact('attribute', 'values'));
    }

    public function update(Request $request, $slug)
    {
        $attribute = Attribute::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|unique:attributes,name,' . $attribute->id,
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|distinct',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
            'values.required' => 'Bạn phải nhập ít nhất một giá trị thuộc tính.',
            'values.array' => 'Giá trị thuộc tính không hợp lệ.',
            'values.*.required' => 'Mỗi giá trị thuộc tính không được để trống.',
            'values.*.distinct' => 'Các giá trị thuộc tính không được trùng nhau.',
        ]);

        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->save();

        $newValues = collect($request->values)->map(fn($v) => trim($v))->filter()->unique();

        // Lấy tất cả value kể cả đã xóa mềm
        $allValues = $attribute->values()->withTrashed()->get();

        // Xoá hoặc khôi phục value
        foreach ($allValues as $value) {
            if (!$newValues->contains($value->value)) {
                $value->forceDelete(); // hoặc $value->delete() nếu dùng soft delete
            } else {
                if ($value->trashed()) {
                    $value->restore(); // khôi phục nếu value từng bị xóa mềm
                }
            }
        }

        // Thêm mới hoặc update value
        foreach ($newValues as $val) {
            $attribute->values()->updateOrCreate(
                ['value' => $val],
                ['slug' => Str::slug($val), 'deleted_at' => null]
            );
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Cập nhật thuộc tính thành công!');
    }

    // Xóa thuộc tính (Soft delete)
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $attribute = Attribute::findOrFail($id);

            // Kiểm tra xem các giá trị thuộc tính có liên kết với bất kỳ biến thể sản phẩm nào không
            $hasProductVariants = $attribute->values()->whereHas('variants', function ($query) {
                $query->withTrashed()->whereNull('product_variants.deleted_at'); // Chỉ kiểm tra với variants chưa bị xóa mềm
            })->exists();

            if ($hasProductVariants) {
                DB::rollBack();
                $message = 'Không thể xóa thuộc tính "' . $attribute->name . '" vì có giá trị thuộc tính đang liên kết với sản phẩm.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            $attribute->delete(); // This will trigger the boot method in Attribute model

            DB::commit();
            $message = 'Đã chuyển thuộc tính "' . $attribute->name . '" vào thùng rác!';
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Lỗi khi xóa thuộc tính: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Xóa nhiều thuộc tính (Soft delete)
    public function bulkDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = explode(',', $request->ids);
            $ids = array_filter($ids, 'is_numeric');

            if (empty($ids)) {
                $message = 'Không có thuộc tính nào được chọn hoặc ID không hợp lệ.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            $deletedCount = 0;
            $notDeletedNames = [];

            foreach ($ids as $id) {
                $attribute = Attribute::find($id);
                if ($attribute) {
                    $hasProductVariants = $attribute->values()->whereHas('variants', function ($query) {
                        $query->withTrashed()->whereNull('product_variants.deleted_at');
                    })->exists();

                    if ($hasProductVariants) {
                        $notDeletedNames[] = $attribute->name;
                    } else {
                        $attribute->delete();
                        $deletedCount++;
                    }
                }
            }

            DB::commit();

            $message = '';
            if ($deletedCount > 0) {
                $message .= 'Đã chuyển ' . $deletedCount . ' thuộc tính vào thùng rác thành công!';
            }
            if (count($notDeletedNames) > 0) {
                if ($deletedCount > 0) {
                    $message .= ' Tuy nhiên, ';
                }
                $message .= 'các thuộc tính sau không thể xóa do có giá trị thuộc tính liên kết với sản phẩm: ' . implode(', ', $notDeletedNames) . '.';
                return response()->json(['success' => true, 'status' => 'warning', 'message' => $message, 'deletedCount' => $deletedCount, 'notDeletedNames' => $notDeletedNames]);
            }

            if ($deletedCount === 0 && count($notDeletedNames) === 0) {
                return response()->json(['success' => false, 'message' => 'Không có thuộc tính nào hợp lệ để xóa.'], 400);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Lỗi xóa hàng loạt thuộc tính: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Hiển thị danh sách các thuộc tính đã xóa mềm
    public function trashed()
    {
        $attributes = Attribute::onlyTrashed()->with('values')->get();
        return view('backend.attributes.trashed', compact('attributes'));
    }

    // Khôi phục một thuộc tính
    public function restore(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $attribute = Attribute::onlyTrashed()->findOrFail($id);
            $attribute->restore();

            DB::commit();
            $message = 'Đã khôi phục thuộc tính "' . $attribute->name . '"!';
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Lỗi khôi phục thuộc tính: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Khôi phục nhiều thuộc tính
    public function bulkRestore(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = explode(',', $request->ids);
            $ids = array_filter($ids, 'is_numeric');

            if (empty($ids)) {
                $message = 'Không có thuộc tính nào được chọn hoặc ID không hợp lệ.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            Attribute::onlyTrashed()->whereIn('id', $ids)->restore();

            DB::commit();
            $message = 'Đã khôi phục ' . count($ids) . ' thuộc tính đã chọn!';
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Lỗi khôi phục hàng loạt thuộc tính: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Xóa vĩnh viễn một thuộc tính
    public function forceDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $attribute = Attribute::onlyTrashed()->findOrFail($id);

            // Kiểm tra xem các giá trị thuộc tính có liên kết với bất kỳ biến thể sản phẩm nào không
            $hasProductVariants = $attribute->values()->withTrashed()->whereHas('variants', function ($query) {
                $query->whereNull('product_variants.deleted_at'); // Chỉ kiểm tra với variants chưa bị xóa mềm
            })->exists();

            if ($hasProductVariants) {
                DB::rollBack();
                $message = 'Không thể xóa vĩnh viễn thuộc tính "' . $attribute->name . '" vì có giá trị thuộc tính đang liên kết với sản phẩm.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            $attribute->forceDelete();

            DB::commit();
            $message = 'Đã xóa vĩnh viễn thuộc tính "' . $attribute->name . '"!';
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Lỗi xóa vĩnh viễn thuộc tính: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Xóa vĩnh viễn nhiều thuộc tính
    public function bulkForceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = explode(',', $request->ids);
            $ids = array_filter($ids, 'is_numeric');

            if (empty($ids)) {
                $message = 'Không có thuộc tính nào được chọn hoặc ID không hợp lệ.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }

            $deletedCount = 0;
            $notDeletedNames = [];

            foreach ($ids as $id) {
                $attribute = Attribute::onlyTrashed()->find($id);
                if ($attribute) {
                    // Kiểm tra xem các giá trị thuộc tính có liên kết với bất kỳ biến thể sản phẩm nào không
                    $hasProductVariants = $attribute->values()->withTrashed()->whereHas('variants', function ($query) {
                        $query->whereNull('product_variants.deleted_at');
                    })->exists();

                    if ($hasProductVariants) {
                        $notDeletedNames[] = $attribute->name;
                    } else {
                        $attribute->forceDelete();
                        $deletedCount++;
                    }
                }
            }

            DB::commit();

            $message = '';
            if ($deletedCount > 0) {
                $message .= 'Đã xóa vĩnh viễn ' . $deletedCount . ' thuộc tính đã chọn!';
            }
            if (count($notDeletedNames) > 0) {
                if ($deletedCount > 0) {
                    $message .= ' Tuy nhiên, ';
                }
                $message .= 'các thuộc tính sau không thể xóa vĩnh viễn do có giá trị thuộc tính liên kết với sản phẩm: ' . implode(', ', $notDeletedNames) . '.';
                return response()->json(['success' => true, 'status' => 'warning', 'message' => $message, 'deletedCount' => $deletedCount, 'notDeletedNames' => $notDeletedNames]);
            }

            if ($deletedCount === 0 && count($notDeletedNames) === 0) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy thuộc tính nào trong thùng rác với các ID đã chọn.'], 400);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Lỗi xóa vĩnh viễn hàng loạt thuộc tính: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Toggle trạng thái (nếu có field trạng thái)
    public function toggleStatus($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->active = !$attribute->active;
        $attribute->save();

        return response()->json(['success' => true, 'status' => $attribute->active]);
    }

    // Toggle trạng thái variant nếu cần
    public function toggleVariantStatus($id)
    {
        // logic toggle variant status nếu dùng variant cho thuộc tính
    }
}
