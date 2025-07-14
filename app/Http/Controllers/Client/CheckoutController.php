<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client\CartItem;
use App\Models\admin\ShippingMethod;
use App\Models\Client\DiscountCode;
use App\Models\admin\Order;
use App\Models\admin\OrderItem;

class CheckoutController extends Controller
{

    // Trang checkout - đã gộp logic chọn sản phẩm và nhiều mã giảm giá
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $addresses = $user ? $user->addresses()->get() : collect();
        $addressId = session('address_id');
        $address = $addressId
            ? $user->addresses()->where('id', $addressId)->first()
            : $user->addresses()->where('is_default', 1)->first();

        // Nhận danh sách ID cart item được chọn từ giỏ hàng
        $selectedRaw = $request->input('selected_cart_item_ids', '');
        if (is_array($selectedRaw)) {
            $selectedIds = array_map('intval', $selectedRaw);
        } else {
            $selectedIds = array_filter(array_map('intval', explode(',', $selectedRaw)));
        }

        if (count($selectedIds) > 0) {
            $cartItems = CartItem::with(['product', 'variant'])
                ->where('user_id', $user->id)
                ->whereIn('id', $selectedIds)
                ->get();
        } else {
            $cartItems = CartItem::with(['product', 'variant'])
                ->where('user_id', $user->id)
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Lấy shipping method
        $shippingMethods = ShippingMethod::whereIn('id', [1, 2])->where('active', 1)->get();
        $shippingMethodId = session('shipping_method_id', 1);
        $shippingMethod = $shippingMethods->firstWhere('id', $shippingMethodId);

        // Lấy mã giảm giá và mã miễn phí vận chuyển từ session
        $orderDiscountCodeStr = session('order_discount_code');
        $freeShippingCodeStr = session('free_shipping_code');

        $codes = [];
        if ($orderDiscountCodeStr) {
            $codes[] = $orderDiscountCodeStr;
        }
        if ($freeShippingCodeStr) {
            $codes[] = $freeShippingCodeStr;
        }

        if (empty($codes)) {
            $appliedDiscountCodes = collect();
        } else {
            $appliedDiscountCodes = DiscountCode::whereIn('code', $codes)
                ->where('active', 1)
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->get();
        }

        // Tính toán giá trị đơn hàng
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
        }

        // Tính tổng tiền giảm giá (chỉ tính với các mã 'order_discount')
        $discountAmount = 0;
        foreach ($appliedDiscountCodes as $discountCode) {
            if ($discountCode->type === 'order_discount') {
                if ($discountCode->discount_type === 'percent') {
                    $amount = $subtotal * ($discountCode->discount_value / 100);
                    if ($discountCode->max_discount_amount) {
                        $amount = min($amount, $discountCode->max_discount_amount);
                    }
                } else {
                    $amount = $discountCode->discount_value;
                }
                $discountAmount += min($amount, $subtotal);
            }
        }
        $discountAmount = min($discountAmount, $subtotal);

        // Tính phí vận chuyển nếu có mã miễn phí vận chuyển
        $shippingCost = $freeShippingCodeStr ? 0 : ($shippingMethod ? $shippingMethod->cost : 0);

        // Tính tổng tiền cuối cùng
        $total = $subtotal + $shippingCost - $discountAmount;

        return view('frontend.checkout.checkout', [
            'addresses' => $addresses,
            'address' => $address,
            'cartItems' => $cartItems,
            'shippingMethods' => $shippingMethods,
            'shippingMethodId' => $shippingMethodId,
            'shippingMethod' => $shippingMethod,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'discountAmount' => $discountAmount,
            'total' => $total,
            'validDiscountCodes' => DiscountCode::where('active', 1)
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->get(),
            'appliedDiscountCodes' => $appliedDiscountCodes,
            'selected_cart_item_ids' => $selectedIds,
        ]);
    }


    // Đặt hàng
    public function processOrder(Request $request)
    {
        $user = Auth::user();

        $validatedAddress = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'address' => 'required|string',
        ]);

        $shipping_method_id = $request->input('shipping_method_id', 1);
        $payment_method = $request->input('payment_method', 'cod');

        // Cập nhật hoặc tạo mới địa chỉ
        if ($request->filled('address_id')) {
            $address = $user->addresses()->findOrFail($request->input('address_id'));
            $address->update($validatedAddress);
        } else {
            $address = $user->addresses()->create($validatedAddress);
        }
        $address->update(['is_default' => true]);
        $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

        // Lấy cart items đã chọn
        $selectedIds = $request->input('selected_cart_item_ids', []);
        if (!is_array($selectedIds)) {
            $selectedIds = explode(',', $selectedIds);
        }
        $selectedIds = array_map('intval', $selectedIds);

        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->when(count($selectedIds) > 0, function ($query) use ($selectedIds) {
                $query->whereIn('id', $selectedIds);
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống!');
        }

        $shippingMethod = ShippingMethod::find($shipping_method_id);
        $originalShippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        // Lấy mã từ session
        $orderDiscountCodeStr = session('order_discount_code');
        $freeShippingCodeStr = session('free_shipping_code');

        $orderDiscountCode = null;
        $freeShippingCode = null;

        if ($orderDiscountCodeStr) {
            $orderDiscountCode = DiscountCode::where('code', $orderDiscountCodeStr)
                ->where('type', '!=', 'free_shipping')
                ->where('active', 1)
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->first();
        }

        if ($freeShippingCodeStr) {
            $freeShippingCode = DiscountCode::where('code', $freeShippingCodeStr)
                ->where('type', 'free_shipping')
                ->where('active', 1)
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->first();
        }

        // Tính tổng tiền hàng
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
        }

        // Tính tiền giảm giá
        $discountAmount = 0;
        if ($orderDiscountCode) {
            if ($orderDiscountCode->discount_type === 'percent') {
                $discountAmount = $subtotal * ($orderDiscountCode->discount_value / 100);
                if ($orderDiscountCode->max_discount_amount) {
                    $discountAmount = min($discountAmount, $orderDiscountCode->max_discount_amount);
                }
            } else {
                $discountAmount = $orderDiscountCode->discount_value;
            }
            $discountAmount = min($discountAmount, $subtotal);
        }

        // Xử lý miễn phí vận chuyển nếu có
        $shippingCost = ($freeShippingCode) ? 0 : $originalShippingCost;

        // Tổng tiền cần thanh toán
        $total = $subtotal + $shippingCost - $discountAmount;

        $bankTransferConfirmed = $request->input('bank_transfer_confirmed', 0);

        // Lưu đơn hàng
        // dd($freeShippingCode, $freeShippingCodeStr);
        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping_method_id,
            'payment_method' => $payment_method,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_code_id' => $orderDiscountCode ? $orderDiscountCode->id : null,
            'free_shipping_code_id' => $freeShippingCode ? $freeShippingCode->id : null,
            'discount_amount' => $discountAmount,
            'total_amount' => $total,
            'status' => 'pending',
            'bank_transfer_confirmed' => ($payment_method === 'bank' && $bankTransferConfirmed) ? 1 : 0,
        ]);

        // Chi tiết sản phẩm
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? '',
                'product_variant_value_id' => $item->variant_id,
                'product_variant_value_name' => $item->variant->name ?? null,
                'product_sku' => $item->product->sku ?? null,
                'product_image' => $item->product->image ?? null,
                'quantity' => $item->quantity,
                'price' => $item->price ?? 0,
                'total' => ($item->price ?? 0) * ($item->quantity ?? 1),
            ]);
        }

        // Xóa giỏ hàng đã đặt
        CartItem::where('user_id', $user->id)
            ->when(count($selectedIds) > 0, function ($query) use ($selectedIds) {
                $query->whereIn('id', $selectedIds);
            })
            ->delete();

        // Xoá session
        session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method_id']);

        return view('frontend.checkout.checkoutsuccess');
    }

    // Lưu/cập nhật địa chỉ độc lập
    public function saveAddress(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address' => 'required|string',
        ]);

        if ($request->filled('address_id')) {
            $address = $user->addresses()->findOrFail($request->input('address_id'));
            $address->update($validated);
        } else {
            $address = $user->addresses()->create($validated);
        }

        return redirect()->route('client.checkout')->with('success', 'Địa chỉ đã được lưu thành công.');
    }

    public function updateShippingMethod(Request $request)
    {
        $shippingMethodId = $request->input('shipping_method_id', 1);
        $orderDiscountCodeStr = $request->input('order_discount_code', null);
        $freeShippingCodeStr = $request->input('free_shipping_code', null);

        // Lưu vào session
        session([
            'shipping_method_id' => $shippingMethodId,
            'order_discount_code' => $orderDiscountCodeStr,
            'free_shipping_code' => $freeShippingCodeStr,
        ]);

        $user = auth()->user();
        $shippingMethod = ShippingMethod::find($shippingMethodId);

        $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $user->id)->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal = $cartItems->sum(function ($item) {
                $price = $item->price ?? $item->product->price ?? 0;
                return $price * ($item->quantity ?? 1);
            });
        }

        // Lấy mã giảm giá và miễn phí vận chuyển từ DB dựa trên session mới lưu
        $orderDiscountCode = null;
        $freeShippingCode = null;

        if ($orderDiscountCodeStr) {
            $orderDiscountCode = DiscountCode::where('code', $orderDiscountCodeStr)
                ->where('type', 'order_discount')
                ->where('active', 1)
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->first();
        }

        if ($freeShippingCodeStr) {
            $freeShippingCode = DiscountCode::where('code', $freeShippingCodeStr)
                ->where('type', 'free_shipping')
                ->where('active', 1)
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->first();
        }

        // Tính tiền giảm giá
        $discountAmount = 0;
        if ($orderDiscountCode) {
            if ($orderDiscountCode->discount_type === 'percent') {
                $discountAmount = $subtotal * ($orderDiscountCode->discount_value / 100);
                if ($orderDiscountCode->max_discount_amount) {
                    $discountAmount = min($discountAmount, $orderDiscountCode->max_discount_amount);
                }
            } else {
                $discountAmount = $orderDiscountCode->discount_value;
            }
            $discountAmount = min($discountAmount, $subtotal);
        }

        // Tính phí vận chuyển trừ miễn phí vận chuyển nếu có
        $shippingCost = $freeShippingCode ? 0 : ($shippingMethod ? $shippingMethod->cost : 0);

        // Tổng tiền
        $total = $subtotal + $shippingCost - $discountAmount;

        return response()->json([
            'shipping_cost' => (int) $shippingCost,
            'total' => (int) $total,
            'discount_amount' => (int) $discountAmount,
            'subtotal' => (int) $subtotal,
        ]);
    }



    public function applyDiscount(Request $request)
    {
        $request->validate([
            'order_discount_code' => 'nullable|string',
            'free_shipping_code' => 'nullable|string',
        ]);

        $orderCodeInput = strtoupper(trim($request->input('order_discount_code', '')));
        $shippingCodeInput = strtoupper(trim($request->input('free_shipping_code', '')));

        // Nếu không có mã nào được chọn → xóa session và trả về
        if (empty($orderCodeInput) && empty($shippingCodeInput)) {
            session()->forget(['order_discount_code', 'free_shipping_code']);
            return response()->json(['success' => true]);
        }

        // Tìm các mã hợp lệ
        $validCodes = DiscountCode::whereIn('code', array_filter([$orderCodeInput, $shippingCodeInput]))
            ->where('active', 1)
            ->where(function ($query) {
                $now = now();
                $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) {
                $now = now();
                $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get();

        if ($validCodes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có mã hợp lệ']);
        }

        // Gán vào session nếu hợp lệ
        $orderCode = $validCodes->firstWhere('code', $orderCodeInput);
        $shippingCode = $validCodes->firstWhere('code', $shippingCodeInput);

        if ($orderCode && $orderCode->type !== 'order_discount') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đơn hàng không hợp lệ']);
        }

        if ($shippingCode && $shippingCode->type !== 'free_shipping') {
            return response()->json(['success' => false, 'message' => 'Mã miễn phí vận chuyển không hợp lệ']);
        }

        if ($orderCode) {
            session(['order_discount_code' => $orderCode->code]);
        } else {
            session()->forget('order_discount_code');
        }

        if ($shippingCode) {
            session(['free_shipping_code' => $shippingCode->code]);
        } else {
            session()->forget('free_shipping_code');
        }

        session()->save();

        return response()->json(['success' => true]);
    }



    public function removeDiscount(Request $request)
    {
        session()->forget(['order_discount_code', 'free_shipping_code']);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('client.checkout')->with('success', 'Đã xoá mã giảm giá!');
    }

    public function bankConfirm(Request $request)
    {
        session(['bank_transfer_confirmed' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Trả về mảng [orderDiscountCode, freeShippingCode]
     */
    private function resolveDiscountCodes(): array
    {
        $codes = session('discount_codes', []);
        if (empty($codes)) return [null, null];

        $found = DiscountCode::whereIn('code', $codes)
            ->where('active', 1)
            ->where(function ($q) {
                $now = now();
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) {
                $now = now();
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get()
            ->keyBy('code');

        $orderDiscount = null;
        $freeShip      = null;

        foreach ($codes as $c) {
            $dc = $found[$c] ?? null;
            if (!$dc) continue;

            if ($dc->type === 'free_shipping' && !$freeShip) {
                $freeShip = $dc;
            } elseif ($dc->type === 'order_discount' && !$orderDiscount) {
                $orderDiscount = $dc;
            }
        }

        return [$orderDiscount, $freeShip];
    }
}
