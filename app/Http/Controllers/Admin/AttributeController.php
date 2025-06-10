<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attribute;
use App\Models\admin\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AttributeController extends Controller
{
    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'values' => 'required|string'
        ], [
            'name.required' => 'Tên thuộc tính bắt buộc',
            'values.required' => 'Nhập giá trị thuộc tính (phân tách bởi dấu phẩy)',
        ]);

        $attr = Attribute::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        $values = explode(',', $request->values);
        foreach ($values as $val) {
            $val = trim($val);
            if ($val) {
                AttributeValue::create([
                    'attribute_id' => $attr->id,
                    'value' => $val,
                    'slug' => Str::slug($val)
                ]);
            }
        }
        return back()->with('success', 'Tạo thuộc tính thành công!');
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
        $attribute = Attribute::where('slug', $slug)->with('values')->firstOrFail();

        return view('backend.attributes.edit', compact('attribute'));
    }

    // Cập nhật thuộc tính
    public function update(Request $request, $slug)
    {
        $attribute = Attribute::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|unique:attributes,name,' . $attribute->id,
            'values' => 'nullable|array',
            'values.*' => 'nullable|string|distinct',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
            'values.*.distinct' => 'Các giá trị thuộc tính không được trùng nhau.',
        ]);

        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->save();

        // Xóa các giá trị cũ
        $attribute->values()->delete();

        // Lưu lại các giá trị mới (bỏ trống sẽ không lưu)
        if ($request->values) {
            foreach ($request->values as $value) {
                $value = trim($value);
                if ($value) {
                    $attribute->values()->create([
                        'value' => $value,
                        'slug' => Str::slug($value),
                    ]);
                }
            }
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Cập nhật thuộc tính thành công!');
    }


    // Xóa thuộc tính
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'Xóa thuộc tính thành công');
    }

    // Xóa nhiều thuộc tính
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids; // mảng id truyền lên

        if ($ids && is_array($ids)) {
            Attribute::whereIn('id', $ids)->delete();
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Xóa nhiều thuộc tính thành công');
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
