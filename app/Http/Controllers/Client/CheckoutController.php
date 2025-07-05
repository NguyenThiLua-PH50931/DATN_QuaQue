<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client\CartItem;
use App\Models\admin\ShippingMethod;
use App\Models\admin\DiscountCode;
use App\Models\admin\Order;
use App\Models\admin\OrderItem;

class CheckoutController extends Controller
{
    // Hiển thị trang checkout
    public function checkout(Request $request)
{
    $user = auth()->user();
    $addresses = $user ? $user->addresses()->get() : collect();
    $addressId = session('address_id');
    $address = $addressId
        ? $user->addresses()->where('id', $addressId)->first()
        : $user->addresses()->where('is_default', 1)->first();

    // ⭐ Fix mạnh tay: ép kiểu chuỗi luôn, dù truyền dạng array hay string
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
        return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    $shippingMethods = ShippingMethod::whereIn('id', [1, 2])->where('active', 1)->get();
    $shippingMethodId = session('shipping_method_id', 1);
    $shippingMethod = $shippingMethods->firstWhere('id', $shippingMethodId);
    $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

    $discountCodeStr = session('discount_code');
    $discountCode = $discountCodeStr ? DiscountCode::where('code', $discountCodeStr)->first() : null;

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
    }

    $discountAmount = 0;
    if ($discountCode) {
        if ($discountCode->discount_type === 'percent') {
            $discountAmount = $subtotal * ($discountCode->discount_value / 100);
            if ($discountCode->max_discount_amount) {
                $discountAmount = min($discountAmount, $discountCode->max_discount_amount);
            }
        } else {
            $discountAmount = $discountCode->discount_value;
        }
        $discountAmount = min($discountAmount, $subtotal);
    }

    $total = $subtotal + $shippingCost - $discountAmount;

    return view('frontend.checkout.checkout', [
        'addresses' => $addresses,
        'address' => $address,
        'cartItems' => $cartItems,
        'shippingMethods' => ShippingMethod::whereIn('id', [1, 2])->where('active', 1)->get(),
        'shippingMethodId' => session('shipping_method_id', 1),
        'shippingMethod' => ShippingMethod::find(session('shipping_method_id', 1)),
        'discountCode' => null, // hoặc load từ session nếu có
        'subtotal' => $cartItems->sum(fn($item) => ($item->price ?? 0) * ($item->quantity ?? 1)),
        'shippingCost' => 30000,
        'discountAmount' => 0,
        'total' => $cartItems->sum(fn($item) => ($item->price ?? 0) * ($item->quantity ?? 1)) + 30000,
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

    if ($request->filled('address_id')) {
        $address = $user->addresses()->findOrFail($request->input('address_id'));
        $address->update($validatedAddress);
    } else {
        $address = $user->addresses()->create($validatedAddress);
    }
    $address->update(['is_default' => true]);
    $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

    // --- FIX: Luôn ép về array và kiểu int ---
    $selectedIds = $request->input('selected_cart_item_ids', []);
    if (!is_array($selectedIds)) {
        $selectedIds = explode(',', $selectedIds);
    }
    $selectedIds = array_map('intval', $selectedIds);

    if (count($selectedIds) > 0) {

        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedIds)
            ->get();
    } else {
        $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $user->id)->get();
    }

    if ($cartItems->isEmpty()) {
        return back()->with('error', 'Giỏ hàng trống!');
    }

    $shippingMethod = ShippingMethod::find($shipping_method_id);
    $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;
    $discountCodeStr = session('discount_code');
    $discountCode = $discountCodeStr ? DiscountCode::where('code', $discountCodeStr)->first() : null;

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
    }

    $discountAmount = 0;
    if ($discountCode) {
        if ($discountCode->discount_type === 'percent') {
            $discountAmount = $subtotal * ($discountCode->discount_value / 100);
            if ($discountCode->max_discount_amount) {
                $discountAmount = min($discountAmount, $discountCode->max_discount_amount);
            }
        } else {
            $discountAmount = $discountCode->discount_value;
        }
        $discountAmount = min($discountAmount, $subtotal);
    }

    $total = $subtotal + $shippingCost - $discountAmount;

    $bankTransferConfirmed = $request->input('bank_transfer_confirmed', 0);

    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $address->id,
        'shipping_method_id' => $shipping_method_id,
        'payment_method' => $payment_method,
        'subtotal' => $subtotal,
        'shipping_cost' => $shippingCost,
        'discount_code_id' => $discountCode ? $discountCode->id : null,
        'discount_amount' => $discountAmount,
        'total_amount' => $total,
        'status' => 'pending',
        'bank_transfer_confirmed' => ($payment_method === 'bank' && $bankTransferConfirmed) ? 1 : 0,
    ]);

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

    if (count($selectedIds) > 0) {

        CartItem::where('user_id', $user->id)
            ->whereIn('id', $selectedIds)
            ->delete();
    } else {
        CartItem::where('user_id', $user->id)->delete();
    }

    session()->forget('discount_code');
    session()->forget('shipping_method_id');

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
        session(['shipping_method_id' => $shippingMethodId]);

        // Tính toán lại chi phí dựa trên shipping method vừa chọn
        $user = auth()->user();
        $shippingMethod = ShippingMethod::find($shippingMethodId);
        $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $user->id)->get();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
        }

        $discountCodeStr = session('discount_code');
        $discountCode = $discountCodeStr ? DiscountCode::where('code', $discountCodeStr)->first() : null;

        $discountAmount = 0;
        if ($discountCode) {
            if ($discountCode->discount_type === 'percent') {
                $discountAmount = $subtotal * ($discountCode->discount_value / 100);
                if ($discountCode->max_discount_amount) {
                    $discountAmount = min($discountAmount, $discountCode->max_discount_amount);
                }
            } else {
                $discountAmount = $discountCode->discount_value;
            }
            $discountAmount = min($discountAmount, $subtotal);
        }

        $total = $subtotal + $shippingCost - $discountAmount;

        return response()->json([
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'discount_amount' => $discountAmount,
            'subtotal' => $subtotal,
        ]);
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->input('discount_code')));
        $discount = DiscountCode::where('code', $code)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$discount) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!']);
            }
            return redirect()->route('client.checkout')->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn!');
        }

        session(['discount_code' => $code]);

        // ✅ Thêm điều kiện AJAX để trả đúng JSON:
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('client.checkout')->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function removeDiscount(Request $request)
    {
        session()->forget('discount_code');
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('client.checkout')->with('success', 'Đã xoá mã giảm giá!');
    }

    public function bankConfirm(Request $request)
    {
        // Nếu có đơn tạm/session thì lấy đúng đơn, ở đây ví dụ lưu flag vào session:
        session(['bank_transfer_confirmed' => true]);
        return response()->json(['success' => true]);
    }
    
}
