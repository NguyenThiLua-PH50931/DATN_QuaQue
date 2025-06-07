<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Coupons;
use Illuminate\Http\Request;
use App\Http\Requests\DiscountCodeRequest;

class CouponsController extends Controller
{
    public function index()
    {
        $coupons = Coupons::whereNull('deleted_at')->get(); // hoặc Coupons::all() nếu đã dùng softDeletes thì nó tự loại bỏ
        return view('backend.coupons.index', compact('coupons'));
    }
    public function create()
    {
        return view('backend.coupons.create'); // Đường dẫn view của bạn, sửa theo đúng thư mục
    }


    public function store(DiscountCodeRequest $request)
    {
        $validated = $request->validated();

        $validated['free_shipping'] = $request->has('free_shipping') ? 1 : 0;
        $validated['active'] = $request->has('active') ? 1 : 0;

        Coupons::create($validated);

        return redirect()->route('admin.coupon.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    public function destroy($id)
    {
        $coupon = Coupons::findOrFail($id);
        $coupon->delete(); // Xóa mềm

        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được xóa (ẩn tạm thời).');
    }

    public function edit($id)
    {
        $coupon = Coupons::findOrFail($id);
        return view('backend.coupons.edit', compact('coupon'));
    }

    // Xử lý cập nhật mã giảm giá
    public function update(Request $request, $id)
    {
        // Tìm mã giảm giá theo ID hoặc trả về lỗi 404
        $coupon = Coupons::findOrFail($id);

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'description'         => 'required|string|max:255',
            'code'                => 'required|string|max:50|unique:discount_codes,code,' . $coupon->id,
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'usage_limit'         => 'required|integer|min:1',
            'discount_type'       => 'required|in:Phần trăm,Tiền cố định',
            'discount_value'      => 'required|numeric|min:0',
            'active'              => 'nullable|boolean',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'used_count'          => 'nullable|integer|min:0',
        ]);

        // Gán giá trị checkbox (checkbox không được check sẽ không có trong request)
        $validated['active'] = $request->has('active') ? 1 : 0;

        // Cập nhật thông tin mã giảm giá
        $coupon->update($validated);

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('admin.coupon.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }
}
