<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\DiscountCode;
use Illuminate\Http\Request;
use App\Models\admin\Product;
use Illuminate\Validation\Rule;

class CouponsController extends Controller
{
    public function index(Request $request)
    {
        $query = DiscountCode::query();

        if ($request->filled('active')) {
            $query->where('active', $request->input('active'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->except('page'));

        return view('backend.coupons.index', [
            'coupons' => $coupons,
            'filterActive' => $request->input('active', ''),
            'filterDateFrom' => $request->input('date_from', ''),
            'filterDateTo' => $request->input('date_to', ''),
            'filterType' => $request->input('type', ''),
        ]);
    }

    public function create()
    {
        $products = Product::all();
        return view('backend.coupons.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Lấy trước kiểu type
        $type = $request->input('type');

        // Tạo rule tuỳ theo type
        $rules = [
            'description' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'type' => 'required|in:order_discount,free_shipping',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'used_count' => 'nullable|integer|min:0',
        ];

        // Chỉ khi là order_discount thì mới yêu cầu 2 trường này
        if ($type === 'order_discount') {
            $rules['discount_type'] = 'required|in:percent,fixed';
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // Cập nhật các trường còn lại
        $validated['active'] = $request->has('active') ? 1 : 0;
        $validated['type'] = $type;

        if ($type === 'free_shipping') {
            $validated['discount_type'] = null;
            $validated['discount_value'] = 0;
            $validated['free_shipping'] = 1;
        } else {
            $validated['free_shipping'] = 0;
        }

        DiscountCode::create($validated);

        return redirect()->route('admin.coupon.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    public function edit($id)
    {
        $coupon = DiscountCode::findOrFail($id);
        $products = Product::all();
        return view('backend.coupons.edit', compact('coupon', 'products'));
    }

    public function update(Request $request, $id)
    {
        $coupon = DiscountCode::findOrFail($id);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('discount_codes', 'code')->ignore($coupon->id)],
            'type' => 'required|in:order_discount,free_shipping',

            'discount_type' => [
                Rule::requiredIf($request->type === 'order_discount'),
                'in:percent,fixed'
            ],
            'discount_value' => [
                Rule::requiredIf($request->type === 'order_discount'),
                'numeric',
                'min:0'
            ],

            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'used_count' => 'nullable|integer|min:0',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;
        $validated['free_shipping'] = $request->type === 'free_shipping' ? 1 : 0;

        if ($validated['free_shipping']) {
            $validated['discount_type'] = null;
            $validated['discount_value'] = 0;
        }

        $coupon->update($validated);

        return redirect()->route('admin.coupon.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy($id)
    {
        $coupon = DiscountCode::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupon.index')->with('success', 'Đã xóa mã giảm giá.');
    }
}
