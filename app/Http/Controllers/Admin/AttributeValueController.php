<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attribute;
use App\Models\admin\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AttributeValueController extends Controller
{

    public function storeQuick(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:100',
        ]);

        $exists = AttributeValue::where('attribute_id', $request->attribute_id)
            ->where('value', $request->value)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Giá trị thuộc tính đã tồn tại.']);
        }

        $attributeValue = AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'slug' => Str::slug($request->value),
        ]);

        return response()->json([
            'success' => true,
            'attributeValue' => $attributeValue,
            'attribute_id' => $request->attribute_id,
        ]);
    }
}
