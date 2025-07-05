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
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Lấy shipping method
        $shippingMethods = ShippingMethod::whereIn('id', [1, 2])->where('active', 1)->get();
        $shippingMethodId = session('shipping_method_id', 1);
        $shippingMethod = $shippingMethods->firstWhere('id', $shippingMethodId);
        $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        // ======= Phần nhiều mã giảm giá =======
        // Lấy danh sách id sản phẩm trong cart
        $productIds = $cartItems->pluck('product_id')->unique()->toArray();

        // Các mã giảm giá hợp lệ hiện tại
        $validDiscountCodes = DiscountCode::where('active', 1)
            ->where(function ($query) {
                $now = now();
                $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) {
                $now = now();
                $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            // Có thể chỉ áp dụng cho các sản phẩm cụ thể (nếu không có bảng trung gian thì bỏ điều kiện này đi)
            ->whereHas('products', function ($q) use ($productIds) {
                $q->whereIn('product_id', $productIds);
            })
            ->get();

        // Mã đã chọn
        $discountCodes = session('discount_codes', []);
        if (empty($discountCodes)) {
            $appliedDiscountCodes = collect();
        } else {
            $appliedDiscountCodes = DiscountCode::whereIn('code', $discountCodes)
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

        // Tính tổng discount (nhiều mã)
        $discountAmount = 0;
        foreach ($appliedDiscountCodes as $discountCode) {
            $productIdsForDiscount = $discountCode->products->pluck('id')->toArray();
            $applicableItems = $cartItems->filter(function ($item) use ($productIdsForDiscount) {
                return in_array($item->product_id, $productIdsForDiscount);
            });

            $applicableSubtotal = 0;
            foreach ($applicableItems as $item) {
                $applicableSubtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
            }

            if ($applicableSubtotal == 0) continue;

            if ($discountCode->discount_type === 'percent') {
                $amount = $applicableSubtotal * ($discountCode->discount_value / 100);
                if ($discountCode->max_discount_amount) {
                    $amount = min($amount, $discountCode->max_discount_amount);
                }
            } else {
                $amount = $discountCode->discount_value;
            }
            $discountAmount += min($amount, $applicableSubtotal);
        }
        $discountAmount = min($discountAmount, $subtotal);

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
            'validDiscountCodes' => $validDiscountCodes,
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

        // Cập nhật địa chỉ
        if ($request->filled('address_id')) {
            $address = $user->addresses()->findOrFail($request->input('address_id'));
            $address->update($validatedAddress);
        } else {
            $address = $user->addresses()->create($validatedAddress);
        }
        $address->update(['is_default' => true]);
        $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

        // Nhận cart items đã chọn
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
            $cartItems = CartItem::with(['product', 'variant'])
                ->where('user_id', $user->id)
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống!');
        }

        $shippingMethod = ShippingMethod::find($shipping_method_id);
        $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        // --- Áp dụng lại discount (giống bên trên) ---
        $discountCodes = session('discount_codes', []);
        if (empty($discountCodes)) {
            $appliedDiscountCodes = collect();
        } else {
            $appliedDiscountCodes = DiscountCode::whereIn('code', $discountCodes)
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

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
        }
        $discountAmount = 0;
        foreach ($appliedDiscountCodes as $discountCode) {
            $productIdsForDiscount = $discountCode->products->pluck('id')->toArray();
            $applicableItems = $cartItems->filter(function ($item) use ($productIdsForDiscount) {
                return in_array($item->product_id, $productIdsForDiscount);
            });

            $applicableSubtotal = 0;
            foreach ($applicableItems as $item) {
                $applicableSubtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
            }

            if ($applicableSubtotal == 0) continue;

            if ($discountCode->discount_type === 'percent') {
                $amount = $applicableSubtotal * ($discountCode->discount_value / 100);
                if ($discountCode->max_discount_amount) {
                    $amount = min($amount, $discountCode->max_discount_amount);
                }
            } else {
                $amount = $discountCode->discount_value;
            }
            $discountAmount += min($amount, $applicableSubtotal);
        }
        $discountAmount = min($discountAmount, $subtotal);

        $total = $subtotal + $shippingCost - $discountAmount;

        $bankTransferConfirmed = $request->input('bank_transfer_confirmed', 0);

        // Lưu order
        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping_method_id,
            'payment_method' => $payment_method,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            // Chỉ lưu mã đầu tiên nếu có, còn lại có thể custom thêm bảng trung gian order-discount (nâng cao)
            'discount_code_id' => $appliedDiscountCodes->first()->id ?? null,
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

        // Xóa cart items đã đặt hàng
        if (count($selectedIds) > 0) {
            CartItem::where('user_id', $user->id)
                ->whereIn('id', $selectedIds)
                ->delete();
        } else {
            CartItem::where('user_id', $user->id)->delete();
        }

        session()->forget('discount_codes');
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

        // Tính lại giá trị đơn hàng
        $user = auth()->user();
        $shippingMethod = ShippingMethod::find($shippingMethodId);
        $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        $cartItems = CartItem::with(['product', 'variant'])->where('user_id', $user->id)->get();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
        }

        $discountCodes = session('discount_codes', []);
        if (empty($discountCodes)) {
            $appliedDiscountCodes = collect();
        } else {
            $appliedDiscountCodes = DiscountCode::whereIn('code', $discountCodes)
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

        $discountAmount = 0;
        foreach ($appliedDiscountCodes as $discountCode) {
            $productIdsForDiscount = $discountCode->products->pluck('id')->toArray();
            $applicableItems = $cartItems->filter(function ($item) use ($productIdsForDiscount) {
                return in_array($item->product_id, $productIdsForDiscount);
            });

            $applicableSubtotal = 0;
            foreach ($applicableItems as $item) {
                $applicableSubtotal += ($item->price ?? 0) * ($item->quantity ?? 1);
            }

            if ($applicableSubtotal == 0) continue;

            if ($discountCode->discount_type === 'percent') {
                $amount = $applicableSubtotal * ($discountCode->discount_value / 100);
                if ($discountCode->max_discount_amount) {
                    $amount = min($amount, $discountCode->max_discount_amount);
                }
            } else {
                $amount = $discountCode->discount_value;
            }
            $discountAmount += min($amount, $applicableSubtotal);
        }
        $discountAmount = min($discountAmount, $subtotal);

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
            'discount_codes' => 'required|array',
            'discount_codes.*' => 'string',
        ]);

        $codesInput = array_map('strtoupper', array_map('trim', $request->input('discount_codes', [])));

        $validCodes = DiscountCode::whereIn('code', $codesInput)
            ->where('active', 1)
            ->where(function ($query) {
                $now = now();
                $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) {
                $now = now();
                $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->pluck('code')
            ->toArray();

        if (empty($validCodes)) {
            return response()->json(['success' => false, 'message' => 'Không có mã giảm giá hợp lệ']);
        }

        session(['discount_codes' => $validCodes]);

        return response()->json(['success' => true]);
    }

    public function removeDiscount(Request $request)
    {
        session()->forget('discount_codes');
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
}
