<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attribute;
use App\Models\admin\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AttributeValueController extends Controller
{

    public function storeQuick(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string',
        ], [
            'attribute_id.required' => 'Bạn chưa chọn thuộc tính.',
            'attribute_id.exists' => 'Thuộc tính không hợp lệ.',
            'value.required' => 'Bạn chưa nhập giá trị thuộc tính.',
            'value.string' => 'Giá trị thuộc tính không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $values = array_filter(array_map('trim', explode(',', $request->value)), fn($v) => $v !== '');
        $addedValues = [];

        foreach ($values as $val) {
            $exists = AttributeValue::where('attribute_id', $request->attribute_id)
                ->where('value', $val)
                ->exists();

            if ($exists) {
                // Nếu trùng thì kệ, không thêm, không báo lỗi
                continue;
            }

            $attributeValue = AttributeValue::create([
                'attribute_id' => $request->attribute_id,
                'value' => $val,
                'slug' => Str::slug($val),
            ]);
            $addedValues[] = $attributeValue;
        }

        return response()->json([
            'success' => true,
            'attribute_id' => $request->attribute_id,
            'attribute_name' => Attribute::find($request->attribute_id)->name ?? 'Unknown',
            'attributeValues' => $addedValues,
        ]);
    }
}
