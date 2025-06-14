<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Coupons;
use Illuminate\Http\Request;
use App\Http\Requests\DiscountCodeRequest;
use App\Models\admin\Product;

class CouponsController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupons::with('products');

        if ($request->filled('active')) {
            $query->where('active', $request->input('active'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        $coupons = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));
        return view('backend.coupons.index', [
            'coupons'    => $coupons,
            'filterActive'   => $request->input('active', ''),
            'filterDateFrom' => $request->input('date_from', ''),
            'filterDateTo'   => $request->input('date_to', ''),
        ]);
    }

    public function create()
    {
        $products = Product::all();
        return view('backend.coupons.create', compact('products')); // Đường dẫn view của bạn, sửa theo đúng thư mục
    }


    public function store(DiscountCodeRequest $request)
    {
        $validated = $request->validated();

        $validated['free_shipping'] = $request->has('free_shipping') ? 1 : 0;
        $validated['active'] = $request->has('active') ? 1 : 0;

        // Tạo mã giảm giá
        $coupon = Coupons::create($validated);

        // Gắn sản phẩm áp dụng (nếu có)
        if ($request->has('products')) {
            $coupon->products()->attach($request->products);
        }

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
        $products = Product::all(); // Lấy tất cả sản phẩm

        return view('backend.coupons.edit', compact('coupon', 'products'));
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
            'discount_type'       => 'required|in:percent,fixed',
            'discount_value'      => 'required|numeric|min:0',
            'active'              => 'nullable|boolean',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'used_count'          => 'nullable|integer|min:0',
            'product_ids'         => 'nullable|array', // <-- thêm dòng này
            'product_ids.*'       => 'exists:products,id', // <-- mỗi id phải tồn tại
        ]);

        // Gán giá trị checkbox (checkbox không được check sẽ không có trong request)
        $validated['active'] = $request->has('active') ? 1 : 0;

        // Cập nhật thông tin mã giảm giá
        $coupon->update($validated);

        // / Cập nhật sản phẩm áp dụng (nếu có gửi lên từ form)
        if ($request->has('product_ids')) {
            $coupon->products()->sync($request->input('product_ids'));
        } else {
            $coupon->products()->detach(); // không chọn sản phẩm nào thì xoá hết
        }

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('admin.coupon.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }
}
