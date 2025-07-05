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
    public function checkout()
    {
        
        $user = auth()->user();
        $addresses = $user ? $user->addresses()->get() : collect();
        $addressId = session('address_id');
        $address = $addressId
            ? $user->addresses()->where('id', $addressId)->first()
            : $user->addresses()->where('is_default', 1)->first();

        // Lấy giỏ hàng từ DB
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->get();
   if ($cartItems->isEmpty()) {
        return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
    }
        $shippingMethods = ShippingMethod::whereIn('id', [1,2])->where('active', 1)->get();
        $shippingMethodId = session('shipping_method_id', 1); // mặc định chọn id=1
        $shippingMethod = $shippingMethods->firstWhere('id', $shippingMethodId);
        $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        $discountCodeStr = session('discount_code');
        $discountCode = $discountCodeStr ? DiscountCode::where('code', $discountCodeStr)->first() : null;

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
        }

        // Discount
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

        return view('frontend.checkout.checkout', compact(
            'addresses', 'address', 'cartItems', 'shippingMethods',
            'shippingMethodId', 'shippingMethod', 'discountCode',
            'subtotal', 'shippingCost', 'discountAmount', 'total'
        ));
    }

    // Đặt hàng
public function processOrder(Request $request)
{
    $user = Auth::user();

    // Validate địa chỉ và shipping/payment
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

    // Xử lý địa chỉ
    if ($request->filled('address_id')) {
        $address = $user->addresses()->findOrFail($request->input('address_id'));
        $address->update($validatedAddress);
    } else {
        $address = $user->addresses()->create($validatedAddress);
    }
    $address->update(['is_default' => true]);
    $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

    // Lấy cart
    $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $user->id)->get();
    if ($cartItems->isEmpty()) {
        return back()->with('error', 'Giỏ hàng trống!');
    }

    // Tính toán lại
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

    // Lấy FLAG BANK TRANSFER từ input, KHÔNG lấy từ session nữa!
    $bankTransferConfirmed = $request->input('bank_transfer_confirmed', 0);

    // Lưu order (bổ sung trường bank_transfer_confirmed)
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
        // ✔ CHỈ lưu bank_transfer_confirmed nếu chọn bank, còn cod thì luôn là 0
        'bank_transfer_confirmed' => ($payment_method === 'bank' && $bankTransferConfirmed) ? 1 : 0,
    ]);

    // Lưu order_items
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

    // Xoá giỏ hàng + flag session
    CartItem::where('user_id', $user->id)->delete();
    session()->forget('discount_code');
    session()->forget('shipping_method_id');
    // session()->forget('bank_transfer_confirmed'); // CÓ THỂ BỎ DÒNG NÀY

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
