<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\DiscountCode;
use Illuminate\Http\Request;
use App\Models\admin\Product;
use Illuminate\Validation\Rule;
use App\Models\DiscountCodeUsage;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr; 

class CouponsController extends Controller
{
    // List
public function index(Request $request)
{
    $query = DiscountCode::query();

    // Lọc theo trạng thái
    if ($request->filled('active')) {
        $query->where('active', $request->input('active'));
    }
    // Lọc theo ngày tạo
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->input('date_from'));
    }
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->input('date_to'));
    }
    // Lọc theo loại mã
    if ($request->filled('type')) {
        $query->where('type', $request->input('type'));
    }
    // **Lọc theo phạm vi áp dụng (scope)**
    if ($request->filled('scope')) {
        $query->where('scope', $request->input('scope'));
    }
    // Lọc theo loại giảm giá (discount_type)
if ($request->filled('discount_type')) {
    $query->where('discount_type', $request->input('discount_type'));
}

    // **Lọc theo từ khóa (code hoặc mô tả)**
    if ($request->filled('q')) {
        $query->where(function ($q) use ($request) {
            $q->where('code', 'like', '%' . $request->q . '%')
              ->orWhere('description', 'like', '%' . $request->q . '%');
        });
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


    // Show create form
    public function create()
    {
        $products = Product::all();
        $users = User::select('id', 'name', 'email')->get();
        return view('backend.coupons.create', compact('products', 'users'));
    }

    // Store coupon
public function store(Request $request)
{
    $type = $request->input('type');
    $scope = $request->input('scope', 'global');
    $discountType = $request->input('discount_type');

    $rules = [
        'description' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:discount_codes,code',
        'type' => 'required|in:order_discount,free_shipping',
        'scope' => 'required|in:global,conditional',
        'used_count' => 'nullable|integer|min:0',
        'usage_limit' => 'required|integer|min:1',
        'min_order_amount' => 'nullable|numeric|min:0',
    ];

    $messages = [
        'description.required' => 'Vui lòng nhập tiêu đề mã giảm giá.',
        'code.required' => 'Vui lòng nhập mã giảm giá.',
        'code.unique' => 'Mã giảm giá đã tồn tại.',
        'usage_limit.required' => 'Vui lòng nhập số lượng.',
    ];

    if ($scope === 'global') {
        $rules['start_date'] = 'required|date';
        $rules['end_date']   = 'required|date|after_or_equal:start_date';
    }

    if ($type === 'order_discount') {
        $rules['discount_type'] = 'required|in:percent,fixed';
        $rules['discount_value'] = 'required|numeric|min:0';
        if ($discountType === 'percent') {
            $rules['max_discount_amount'] = 'required|numeric|min:0.01';
            $messages['max_discount_amount.required'] = 'Bạn phải nhập giá trị giảm tối đa khi chọn giảm theo phần trăm.';
        } else {
            $rules['max_discount_amount'] = 'nullable|numeric|min:0';
        }
    } else {
        // free_shipping
        $rules['discount_type'] = 'nullable';
        $rules['discount_value'] = 'nullable';
        $rules['max_discount_amount'] = 'nullable';
    }

    if ($scope === 'conditional') {
        $rules['condition_type'] = [
            'required',
            'string',
            'max:50',
            Rule::in(['new_user_30d', 'first_order']),
        ];
        $request->merge(['usage_limit' => 1]);
    }

    $validated = $request->validate($rules, $messages);

    // Nếu là điều kiện và là mã freeship thì các trường giảm giá để null, nhưng GIỮ min_order_amount
    if ($scope === 'conditional' && $type === 'free_shipping') {
        $validated['discount_type'] = null;
        $validated['discount_value'] = null;
        $validated['max_discount_amount'] = null;
    }

    $validated['active'] = $request->has('active') ? 1 : 0;
    $validated['free_shipping'] = ($type === 'free_shipping') ? 1 : 0;

    // Luôn để min_order_amount là 0 nếu null
    if (!isset($validated['min_order_amount']) || $validated['min_order_amount'] === null) {
        $validated['min_order_amount'] = 0;
    }

    DiscountCode::create($validated);

    return redirect()->route('admin.coupon.index')->with('success', 'Tạo mã giảm giá thành công!');
}




    // Show edit form
public function edit($id)
{
    $coupon = DiscountCode::findOrFail($id);
    $products = Product::all();
    return view('backend.coupons.edit', compact('coupon', 'products'));
}

public function update(Request $request, $id)
{
    $coupon = DiscountCode::findOrFail($id);
    $type = $request->input('type');
    $scope = $request->input('scope', 'global');
    $discountType = $request->input('discount_type');

    $rules = [
        'description' => 'required|string|max:255',
        'code' => ['required', 'string', 'max:50', Rule::unique('discount_codes', 'code')->ignore($coupon->id)],
        'type' => 'required|in:order_discount,free_shipping',
        'scope' => 'required|in:global,conditional',
        'used_count' => 'nullable|integer|min:0',
        'usage_limit' => 'required|integer|min:1',
        'min_order_amount' => 'nullable|numeric|min:0',
    ];

    $messages = [
        'description.required' => 'Vui lòng nhập tiêu đề mã giảm giá.',
        'code.required' => 'Vui lòng nhập mã giảm giá.',
        'code.unique' => 'Mã giảm giá đã tồn tại.',
        'usage_limit.required' => 'Vui lòng nhập số lượng.',
    ];

    if ($scope === 'global') {
        $rules['start_date'] = 'required|date';
        $rules['end_date']   = 'required|date|after_or_equal:start_date';
    }

    if ($type === 'order_discount') {
        $rules['discount_type'] = 'required|in:percent,fixed';
        $rules['discount_value'] = 'required|numeric|min:0';
        if ($discountType === 'percent') {
            $rules['max_discount_amount'] = 'required|numeric|min:0.01';
            $messages['max_discount_amount.required'] = 'Bạn phải nhập giá trị giảm tối đa khi chọn giảm theo phần trăm.';
        } else {
            $rules['max_discount_amount'] = 'nullable|numeric|min:0';
        }
    } else {
        // free_shipping
        $rules['discount_type'] = 'nullable';
        $rules['discount_value'] = 'nullable';
        $rules['max_discount_amount'] = 'nullable';
    }

    if ($scope === 'conditional') {
        $rules['condition_type'] = [
            'required',
            'string',
            'max:50',
            Rule::in(['new_user_30d', 'first_order']),
        ];
        $request->merge(['usage_limit' => 1]);
    }

    $validated = $request->validate($rules, $messages);

    // Nếu là điều kiện và là mã freeship thì các trường giảm giá để null, nhưng GIỮ min_order_amount
    if ($scope === 'conditional' && $type === 'free_shipping') {
        $validated['discount_type'] = null;
        $validated['discount_value'] = null;
        $validated['max_discount_amount'] = null;
    }

    $validated['active'] = $request->has('active') ? 1 : 0;
    $validated['free_shipping'] = ($type === 'free_shipping') ? 1 : 0;

    // Luôn để min_order_amount là 0 nếu null
    if (!isset($validated['min_order_amount']) || $validated['min_order_amount'] === null) {
        $validated['min_order_amount'] = 0;
    }

    $coupon->update($validated);

    return redirect()->route('admin.coupon.index')->with('success', 'Cập nhật mã giảm giá thành công!');
}


    // Hàm áp dụng mã cho đơn hàng
    public function applyDiscountCode(Request $request)
    {
        $user = auth()->user();
        $code = $request->input('code');
        $orderTotal = $request->input('order_total');

        // Tìm mã hợp lệ
        $coupon = DiscountCode::where('code', $code)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Mã không hợp lệ hoặc đã hết hạn!'], 400);
        }

        // Check lượt dùng tổng
        if ($coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['error' => 'Mã đã hết lượt sử dụng!'], 400);
        }

        // Check theo scope & điều kiện đặc biệt (conditional)
        if ($coupon->scope === 'conditional') {
            $message = $this->checkCouponCondition($coupon, $user);
            if ($message !== true) {
                return response()->json(['error' => $message], 400);
            }
        }

        // Check min order amount
        if ($orderTotal < $coupon->min_order_amount) {
            return response()->json(['error' => 'Chưa đạt giá trị tối thiểu để dùng mã!'], 400);
        }

        // (Có thể check thêm nhiều logic như sản phẩm hợp lệ...)

        // --- Áp dụng thành công ---
        // Tăng số lần sử dụng
        $coupon->increment('used_count');
        // Ghi lại vào bảng usage
        DiscountCodeUsage::create([
            'discount_code_id' => $coupon->id,
            'user_id' => $user->id,
            'order_id' => null, // sẽ cập nhật sau khi đơn được đặt thành công
            'used_at' => now(),
        ]);

        // Tính số tiền được giảm (có thể dùng hàm calculateDiscount của model)
        $discountAmount = $coupon->calculateDiscount($orderTotal);

        return response()->json([
            'success' => 'Áp dụng mã thành công!',
            'discount_amount' => $discountAmount,
            'coupon' => $coupon
        ]);
    }

    // Hàm hoàn lại số lượt dùng khi hủy đơn
    public function revertDiscountCodeUsage($orderId)
    {
        // Lấy record usage
        $usage = DiscountCodeUsage::where('order_id', $orderId)->first();
        if ($usage) {
            $coupon = $usage->discountCode;
            if ($coupon && $coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }
            $usage->delete();
        }
        // Trả về response hoặc dùng cho job/command đều được
        return true;
    }

    // -- Hàm xử lý điều kiện theo condition_type --
    protected function checkCouponCondition($coupon, $user)
    {
        switch ($coupon->condition_type) {
            case 'new_user_30d':
                if (now()->diffInDays($user->created_at) >= 30) {
                    return 'Chỉ dành cho tài khoản đăng ký dưới 30 ngày!';
                }
                break;
            case 'first_order':
                if ($user->orders()->exists()) {
                    return 'Chỉ áp dụng cho đơn hàng đầu tiên!';
                }
                break;
            case 'loyal_customer_3':
                $count = $user->orders()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
                if ($count < 3 || $count > 5) {
                    return 'Bạn chưa đủ số đơn trong 1 tuần!';
                }
                break;
            case 'high_spender_2m':
                $total = $user->orders()
                    ->where('status', 'completed')
                    ->sum('total_amount');
                if ($total < 2000000) {
                    return 'Bạn chưa đạt tổng chi tiêu trên 2 triệu!';
                }
                break;
            case 'inactive_15d':
                $lastOrder = $user->orders()->latest('created_at')->first();
                if (!$lastOrder || now()->diffInDays($lastOrder->created_at) < 15) {
                    return 'Bạn chưa đủ điều kiện không mua trong 15 ngày!';
                }
                break;
            default:
                return 'Điều kiện không hợp lệ!';
        }
        return true;
    }
}
