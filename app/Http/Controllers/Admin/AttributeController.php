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
}
