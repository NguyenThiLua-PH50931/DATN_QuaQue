<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DiscountCode;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use Illuminate\Validation\Rule;
use App\Models\Admin\DiscountCodeUsage;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

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
        // Lọc theo phạm vi áp dụng (scope)
        if ($request->filled('scope')) {
            $query->where('scope', $request->input('scope'));
        }
        // Lọc theo loại giảm giá (discount_type)
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->input('discount_type'));
        }
        // Lọc theo từ khóa (code hoặc mô tả)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qq) use ($q) {
                $qq->where('code', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $coupons = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('backend.coupons.index', [
            'coupons'             => $coupons,
            'filterActive'        => $request->input('active', ''),
            'filterDateFrom'      => $request->input('date_from', ''),
            'filterDateTo'        => $request->input('date_to', ''),
            'filterType'          => $request->input('type', ''),
            'filterScope'         => $request->input('scope', ''),
            'filterDiscountType'  => $request->input('discount_type', ''),
            'q'                   => $request->input('q', ''),
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
    // Chuẩn hoá trước khi validate
    $code = strtoupper(preg_replace('/\s+/', '', (string) $request->input('code', '')));
    $request->merge(['code' => $code]);

    $type  = $request->input('type');
    $scope = $request->input('scope', 'global');

    // Conditional: khoá ngày + usage_limit = 1
    if ($scope === 'conditional') {
        $request->merge([
            'usage_limit' => 1,
            'start_date'  => null,
            'end_date'    => null,
        ]);
    }

    // Freeship: bỏ các trường giảm giá
    if ($type === 'free_shipping') {
        $request->merge([
            'discount_type'        => null,
            'discount_value'       => null,
            'max_discount_amount'  => null,
        ]);
    }

    // Mặc định min_order_amount = 0 nếu để trống
    if (!$request->filled('min_order_amount')) {
        $request->merge(['min_order_amount' => 0]);
    }

    $rules = [
        'description'        => ['required','string','max:255'],
        'code'               => ['required','string','max:50','unique:discount_codes,code','regex:/^[A-Z0-9_-]+$/'],
        'type'               => ['required','in:order_discount,free_shipping'],
        'scope'              => ['required','in:global,conditional'],
        'usage_limit'        => ['required','integer','min:1'],
        'used_count'         => ['nullable','integer','min:0'],
        'min_order_amount'   => ['nullable','numeric','min:0'],
        'start_date'         => ['nullable','date'],
        'end_date'           => ['nullable','date','after_or_equal:start_date'],
        'discount_type'      => ['nullable','in:percent,fixed'],
        'discount_value'     => ['nullable','numeric','min:0.01'],
        'max_discount_amount'=> ['nullable','numeric','min:0'],
        'condition_type'     => ['nullable', Rule::in(['new_user_30d','first_order'])],
        'active'             => ['nullable','boolean'],
    ];

    $messages = [
        'description.required'        => 'Vui lòng nhập tiêu đề mã giảm giá.',
        'code.required'               => 'Vui lòng nhập mã giảm giá.',
        'code.unique'                 => 'Mã giảm giá đã tồn tại.',
        'code.regex'                  => 'Mã chỉ gồm chữ, số, gạch nối (-) hoặc gạch dưới (_), không có khoảng trắng.',
        'type.required'               => 'Vui lòng chọn loại mã.',
        'scope.required'              => 'Vui lòng chọn phạm vi áp dụng.',
        'usage_limit.required'        => 'Vui lòng nhập số lượng.',
        'start_date.required'         => 'Vui lòng chọn ngày bắt đầu.',
        'end_date.required'           => 'Vui lòng chọn ngày kết thúc.',
        'end_date.after_or_equal'     => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        'discount_type.required'      => 'Vui lòng chọn loại giảm giá.',
        'discount_value.required'     => 'Vui lòng nhập giá trị giảm.',
        'discount_value.max'          => 'Phần trăm giảm giá không được vượt quá 100%.',
        'discount_value.min'          => 'Giá trị giảm phải lớn hơn 0.',
        'max_discount_amount.required'=> 'Bạn phải nhập giá trị giảm tối đa khi chọn giảm theo phần trăm.',
        'condition_type.required'     => 'Vui lòng chọn điều kiện áp dụng.',
    ];

    $attributes = [
        'description'         => 'Tiêu đề mã giảm giá',
        'code'                => 'Mã giảm giá',
        'type'                => 'Loại mã',
        'scope'               => 'Phạm vi áp dụng',
        'usage_limit'         => 'Số lượng',
        'min_order_amount'    => 'Giá trị đơn hàng tối thiểu',
        'start_date'          => 'Ngày bắt đầu',
        'end_date'            => 'Ngày kết thúc',
        'discount_type'       => 'Loại giảm giá',
        'discount_value'      => 'Giá trị giảm',
        'max_discount_amount' => 'Giá trị giảm tối đa',
        'condition_type'      => 'Điều kiện áp dụng',
    ];

    $validator = Validator::make($request->all(), $rules, $messages, $attributes);

    // Chỉ required ngày khi scope=global
    $validator->sometimes('start_date', 'required', fn($input) => $input->scope === 'global');
    $validator->sometimes('end_date',   'required', fn($input) => $input->scope === 'global');

    // Chỉ required giảm giá khi type=order_discount
    $validator->sometimes('discount_type',  'required', fn($input) => $input->type === 'order_discount');
    $validator->sometimes('discount_value', 'required', fn($input) => $input->type === 'order_discount');

    // Nhánh percent: 1..100 + bắt buộc max_discount_amount
    $validator->sometimes('discount_value', 'numeric|min:1|max:100', function ($input) {
        return $input->type === 'order_discount' && $input->discount_type === 'percent';
    });
    $validator->sometimes('max_discount_amount', 'required|numeric|min:0.01', function ($input) {
        return $input->type === 'order_discount' && $input->discount_type === 'percent';
    });

    // Nhánh fixed: >= 0.01
    $validator->sometimes('discount_value', 'numeric|min:0.01', function ($input) {
        return $input->type === 'order_discount' && $input->discount_type === 'fixed';
    });

    // Chỉ required condition_type khi scope=conditional
    $validator->sometimes('condition_type', 'required', fn($input) => $input->scope === 'conditional');

    $data = $validator->validate();

    // Map cờ
    $data['active']        = $request->boolean('active') ? 1 : 0;
    $data['free_shipping'] = ($data['type'] === 'free_shipping') ? 1 : 0;
    $data['min_order_amount'] = $data['min_order_amount'] ?? 0;

    DiscountCode::create($data);

    return redirect()->route('admin.coupon.index')->with('success', 'Tạo mã giảm giá thành công!');
}


    // Show edit form
    public function edit($id)
    {
        $coupon   = DiscountCode::findOrFail($id);
        $products = Product::all();
        return view('backend.coupons.edit', compact('coupon', 'products'));
    }

   public function update(Request $request, $id)
{
    $coupon = DiscountCode::findOrFail($id);

    // Chuẩn hoá code
    $code = strtoupper(preg_replace('/\s+/', '', (string) $request->input('code', '')));
    $request->merge(['code' => $code]);

    $type  = $request->input('type');
    $scope = $request->input('scope', 'global');

    if ($scope === 'conditional') {
        $request->merge([
            'usage_limit' => 1,
            'start_date'  => null,
            'end_date'    => null,
        ]);
    }

    if ($type === 'free_shipping') {
        $request->merge([
            'discount_type'        => null,
            'discount_value'       => null,
            'max_discount_amount'  => null,
        ]);
    }

    if (!$request->filled('min_order_amount')) {
        $request->merge(['min_order_amount' => 0]);
    }

    $rules = [
        'description'         => ['required','string','max:255'],
        'code'                => ['required','string','max:50', Rule::unique('discount_codes','code')->ignore($coupon->id), 'regex:/^[A-Z0-9_-]+$/'],
        'type'                => ['required','in:order_discount,free_shipping'],
        'scope'               => ['required','in:global,conditional'],
        'usage_limit'         => ['required','integer','min:1'],
        'used_count'          => ['nullable','integer','min:0'],
        'min_order_amount'    => ['nullable','numeric','min:0'],
        'start_date'          => ['nullable','date'],
        'end_date'            => ['nullable','date','after_or_equal:start_date'],
        'discount_type'       => ['nullable','in:percent,fixed'],
        'discount_value'      => ['nullable','numeric','min:0.01'],
        'max_discount_amount' => ['nullable','numeric','min:0'],
        'condition_type'      => ['nullable', Rule::in(['new_user_30d','first_order'])],
        'active'              => ['nullable','boolean'],
    ];

    $messages  = [
        'description.required'        => 'Vui lòng nhập tiêu đề mã giảm giá.',
        'code.required'               => 'Vui lòng nhập mã giảm giá.',
        'code.unique'                 => 'Mã giảm giá đã tồn tại.',
        'code.regex'                  => 'Mã chỉ gồm chữ, số, gạch nối (-) hoặc gạch dưới (_), không có khoảng trắng.',
        'usage_limit.required'        => 'Vui lòng nhập số lượng.',
        'start_date.required'         => 'Vui lòng chọn ngày bắt đầu.',
        'end_date.required'           => 'Vui lòng chọn ngày kết thúc.',
        'end_date.after_or_equal'     => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        'discount_type.required'      => 'Vui lòng chọn loại giảm giá.',
        'discount_value.required'     => 'Vui lòng nhập giá trị giảm.',
        'discount_value.max'          => 'Phần trăm giảm giá không được vượt quá 100%.',
        'discount_value.min'          => 'Giá trị giảm phải lớn hơn 0.',
        'max_discount_amount.required'=> 'Bạn phải nhập giá trị giảm tối đa khi chọn giảm theo phần trăm.',
        'condition_type.required'     => 'Vui lòng chọn điều kiện áp dụng.',
    ];

    $attributes = [
        'description'         => 'Tiêu đề mã giảm giá',
        'code'                => 'Mã giảm giá',
        'type'                => 'Loại mã',
        'scope'               => 'Phạm vi áp dụng',
        'usage_limit'         => 'Số lượng',
        'min_order_amount'    => 'Giá trị đơn hàng tối thiểu',
        'start_date'          => 'Ngày bắt đầu',
        'end_date'            => 'Ngày kết thúc',
        'discount_type'       => 'Loại giảm giá',
        'discount_value'      => 'Giá trị giảm',
        'max_discount_amount' => 'Giá trị giảm tối đa',
        'condition_type'      => 'Điều kiện áp dụng',
    ];

    $validator = Validator::make($request->all(), $rules, $messages, $attributes);

    $validator->sometimes('start_date', 'required', fn($input) => $input->scope === 'global');
    $validator->sometimes('end_date',   'required', fn($input) => $input->scope === 'global');

    $validator->sometimes('discount_type',  'required', fn($input) => $input->type === 'order_discount');
    $validator->sometimes('discount_value', 'required', fn($input) => $input->type === 'order_discount');

    $validator->sometimes('discount_value', 'numeric|min:1|max:100', function ($input) {
        return $input->type === 'order_discount' && $input->discount_type === 'percent';
    });
    $validator->sometimes('max_discount_amount', 'required|numeric|min:0.01', function ($input) {
        return $input->type === 'order_discount' && $input->discount_type === 'percent';
    });

    $validator->sometimes('discount_value', 'numeric|min:0.01', function ($input) {
        return $input->type === 'order_discount' && $input->discount_type === 'fixed';
    });

    $validator->sometimes('condition_type', 'required', fn($input) => $input->scope === 'conditional');

    $data = $validator->validate();

    $data['active']           = $request->boolean('active') ? 1 : 0;
    $data['free_shipping']    = ($data['type'] === 'free_shipping') ? 1 : 0;
    $data['min_order_amount'] = $data['min_order_amount'] ?? 0;

    $coupon->update($data);

    return redirect()->route('admin.coupon.index')->with('success', 'Cập nhật mã giảm giá thành công!');
}

    // Áp dụng mã cho đơn hàng
    public function applyDiscountCode(Request $request)
    {
        $user       = auth()->user();
        $code       = $request->input('code');
        $orderTotal = (float) $request->input('order_total');

        // Tìm mã hợp lệ
        $coupon = DiscountCode::where('code', $code)
            ->where('active', 1)
            ->where(function ($q) {
                // Cho phép mã không có ngày (NULL) hoặc trong khung ngày
                $q->where(function ($q1) {
                    $q1->whereNull('start_date')->orWhere('start_date', '<=', now());
                })->where(function ($q2) {
                    $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
                });
            })
            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Mã không hợp lệ hoặc đã hết hạn!'], 400);
        }

        // Check lượt dùng tổng
        if ($coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['error' => 'Mã đã hết lượt sử dụng!'], 400);
        }

        // Check theo scope & điều kiện đặc biệt
        if ($coupon->scope === 'conditional') {
            $message = $this->checkCouponCondition($coupon, $user);
            if ($message !== true) {
                return response()->json(['error' => $message], 400);
            }
        }

        // Check min order amount
        if ($orderTotal < (float) $coupon->min_order_amount) {
            return response()->json(['error' => 'Chưa đạt giá trị tối thiểu để dùng mã!'], 400);
        }

        // Tính số tiền được giảm
        $discountAmount = $coupon->calculateDiscount($orderTotal);

        /**
         * Lưu ý luồng chuẩn:
         * - KHÔNG nên tăng used_count ngay tại đây (chỉ áp dụng tạm thời ở giỏ/checkout).
         * - Chỉ tăng used_count + tạo DiscountCodeUsage khi đơn hàng được tạo/thành công.
         *
         * Nếu vẫn muốn "giữ chỗ" tạm thời, có thể tạo Usage với order_id = null và dọn dẹp sau.
         * Ở đây, để an toàn, mình chỉ trả về kết quả giảm giá, KHÔNG tăng used_count.
         */

        return response()->json([
            'success'          => 'Áp dụng mã thành công!',
            'discount_amount'  => $discountAmount,
            'coupon'           => $coupon
        ]);
    }

    // Gán mã cho đơn khi đặt hàng thành công (gợi ý dùng trong OrderController)
    public function attachDiscountToOrder($orderId, $code)
    {
        $user   = auth()->user();
        $coupon = DiscountCode::where('code', $code)->first();

        if (!$coupon) return;

        // tăng used_count và tạo usage khi đơn đã hình thành
        $coupon->increment('used_count');

        DiscountCodeUsage::create([
            'discount_code_id' => $coupon->id,
            'user_id'          => $user?->id,
            'order_id'         => $orderId,
            'used_at'          => now(),
        ]);
    }

    // Hoàn lượt dùng khi hủy đơn
    public function revertDiscountCodeUsage($orderId)
    {
        // 1) ưu tiên theo order_id
        $usage = DiscountCodeUsage::where('order_id', $orderId)->first();

        // 2) fallback: nếu không có order_id, thử tìm usage "tạm" gần đây của user để trả (tránh giữ chỗ)
        if (!$usage && auth()->check()) {
            $usage = DiscountCodeUsage::where('user_id', auth()->id())
                ->whereNull('order_id')
                ->where('used_at', '>=', now()->subMinutes(30))
                ->latest('used_at')
                ->first();
        }

        if ($usage) {
            $coupon = $usage->discountCode;
            if ($coupon && $coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }
            $usage->delete();
        }

        return true;
    }

    // Điều kiện theo condition_type
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
            // Các case dưới đây hiện chưa mở trong UI, giữ làm dự phòng tương lai
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

    public function destroy($id)
    {
        $coupon = DiscountCode::findOrFail($id);
        $coupon->delete(); // Soft delete nếu model có SoftDeletes
        return redirect()->route('admin.coupon.index')
            ->with('success', 'Xóa mã giảm giá thành công!');
    }

    // Danh sách trong thùng rác
    public function trashed(Request $request)
    {
        $query = DiscountCode::onlyTrashed();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qq) use ($q) {
                $qq->where('code', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('scope')) {
            $query->where('scope', $request->input('scope'));
        }
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->input('discount_type'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('deleted_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('deleted_at', '<=', $request->input('date_to'));
        }

        $coupons = $query->orderBy('deleted_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('backend.coupons.trashed', [
            'coupons'            => $coupons,
            'filterType'         => $request->input('type', ''),
            'filterScope'        => $request->input('scope', ''),
            'filterDiscountType' => $request->input('discount_type', ''),
            'filterDateFrom'     => $request->input('date_from', ''),
            'filterDateTo'       => $request->input('date_to', ''),
            'q'                  => $request->input('q', ''),
        ]);
    }

    public function restore($id)
    {
        $coupon = DiscountCode::onlyTrashed()->findOrFail($id);
        $coupon->restore();

        return redirect()
            ->route('admin.coupon.trashed')
            ->with('success', 'Khôi phục mã giảm giá thành công!');
    }

    public function forceDelete($id)
    {
        $coupon = DiscountCode::onlyTrashed()->findOrFail($id);
        $coupon->forceDelete();

        return redirect()
            ->route('admin.coupon.trashed')
            ->with('success', 'Đã xóa vĩnh viễn mã giảm giá!');
    }

    // Kiểm tra trạng thái tự động xóa
    public function checkAutoDeleteStatus()
    {
        $trashedCoupons = DiscountCode::onlyTrashed()->get();

        $total = $trashedCoupons->count();
        $willBeDeletedSoon = $trashedCoupons->where('will_be_deleted_soon', true)->count();

        $daysUntilAutoDelete = [];
        foreach ($trashedCoupons as $coupon) {
            $daysLeft = $coupon->days_until_auto_delete;
            if ($daysLeft !== null && $daysLeft >= 0) {
                $daysUntilAutoDelete[] = [
                    'coupon_id'       => $coupon->id,
                    'code'            => $coupon->code,
                    'days_left'       => $daysLeft,
                    'auto_delete_at'  => $coupon->auto_delete_at
                ];
            }
        }

        if ($total === 0) {
            return response()->json([
                'total'                   => 0,
                'will_be_deleted_soon'    => 0,
                'days_until_auto_delete'  => [],
                'message'                 => 'Không có mã giảm giá nào đã xóa mềm.'
            ]);
        }

        return response()->json([
            'total'                  => $total,
            'will_be_deleted_soon'   => $willBeDeletedSoon,
            'days_until_auto_delete' => $daysUntilAutoDelete
        ]);
    }
}
