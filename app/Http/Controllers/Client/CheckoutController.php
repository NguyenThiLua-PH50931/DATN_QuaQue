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
    /**
     * Hiển thị trang Checkout (chọn địa chỉ, áp mã giảm giá, chọn phương thức vận chuyển...)
     */
public function checkout(Request $request)
{
    $user = auth()->user();
    if (!$user) return redirect()->route('login');

    // Lấy toàn bộ địa chỉ giao hàng
    $addresses = $user->addresses()->get();

    // Kiểm tra callback từ MoMo
    $isMomoCallback = $request->has('orderId') && $request->has('resultCode');
    if ($isMomoCallback && $request->input('resultCode') == 0) {
        // --- MoMo thanh toán thành công, tạo đơn luôn và chuyển đến trang thành công ---
        $orderId = $request->input('orderId');
        $selectedIds = session('momo_selected_cart_item_ids', []);
        if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
        $selectedIds = array_filter(array_map('intval', $selectedIds));

        $addressId = session('address_id');
        $address = $addressId
            ? $user->addresses()->where('id', $addressId)->first()
            : $user->addresses()->where('is_default', 1)->first();

        if (!$address) {
            return redirect()->route('client.checkout')->with('error', 'Bạn cần thêm địa chỉ giao hàng!');
        }

        $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống hoặc sản phẩm không hợp lệ!');
        }

        // Lấy các thông tin khuyến mãi
        $shippingMethodId = session('shipping_method', 1);
        $shippingMethod = \App\Models\admin\ShippingMethod::find($shippingMethodId);
        $shippingCost = $shippingMethod ? $shippingMethod->cost : 0;

        $orderDiscountCodeStr = session('order_discount_code');
        $freeShippingCodeStr  = session('free_shipping_code');
        $orderDiscountCode = null;
        $freeShippingCode  = null;
        if ($orderDiscountCodeStr) {
            $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
                ->where('type', '!=', 'free_shipping')
                ->where('active', 1)
                ->first();
        }
        if ($freeShippingCodeStr) {
            $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
                ->where('type', 'free_shipping')
                ->where('active', 1)
                ->first();
        }

        $subtotal = $cartItems->sum(function ($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 1);
        });

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

        $shippingCost = $freeShippingCode ? 0 : $shippingCost;
        $total = $subtotal + $shippingCost - $discountAmount;

$order = \App\Models\admin\Order::create([
    'user_id'               => $user->id,
    'recipient_name'        => $address->recipient_name,
    'phone'                 => $address->phone,
    'full_address'          => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
    'shipping_method' => $shippingMethod->name,
    'payment_method'        => 'momo',
    'discount_code'         => $orderDiscountCode ? $orderDiscountCode->code : null, // <-- SỬA chỗ này
    'free_shipping_code' => $freeShippingCode ? $freeShippingCode->code : null,

    'discount_amount'       => $discountAmount,
    'total_amount'          => $total,
    'shipping_cost'         => $shippingCost,
    'status'                => 'confirmed',
    'payment_status'        => 'paid',
]);


        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id'                => $item->product_id,
                'product_name'              => $item->product->name ?? '',
                'product_variant_value_id'  => $item->variant_id,
                'product_variant_value_name'=> $item->variant->name ?? null,
                'product_sku'               => $item->product->sku ?? null,
                'product_image'             => $item->product->image ?? null,
                'quantity'                  => $item->quantity,
                'price'                     => $item->price ?? 0,
                'total'                     => ($item->price ?? 0) * ($item->quantity ?? 1),
            ]);
            // Trừ kho
            if ($item->variant_id) {
                $variant = \App\Models\admin\ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->stock = max(0, $variant->stock - $item->quantity);
                    $variant->save();
                }
            } else {
                $product = \App\Models\admin\Product::find($item->product_id);
                if ($product) {
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();
                }
            }
        }

        // Xoá cart item đã đặt hàng và clear session
        \App\Models\Client\CartItem::where('user_id', $user->id)->whereIn('id', $selectedIds)->delete();
        session()->forget(['momo_selected_cart_item_ids', 'order_discount_code', 'free_shipping_code', 'shipping_method']);

        return redirect()->route('client.checkout.success');
    }

    // Không phải callback MoMo thì chỉ hiển thị trang checkout như bình thường
    // Xoá mã giảm giá nếu không có sản phẩm chọn
    if (!$request->has('selected_cart_item_ids') && !$isMomoCallback) {
        session()->forget(['order_discount_code', 'free_shipping_code']);
    }

    $orderDiscountCodeStr = session('order_discount_code');
    $freeShippingCodeStr  = session('free_shipping_code');
    $orderDiscountCode = null;
    $freeShippingCode  = null;
    if ($orderDiscountCodeStr) {
        $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
            ->where('type', '!=', 'free_shipping')
            ->where('active', 1)
            ->first();
    }
    if ($freeShippingCodeStr) {
        $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
            ->where('type', 'free_shipping')
            ->where('active', 1)
            ->first();
    }

    $addressId = session('address_id');
    $address = $addressId
        ? $user->addresses()->where('id', $addressId)->first()
        : $user->addresses()->where('is_default', 1)->first();

    // Lấy cart items đã chọn hoặc toàn bộ
    $selectedRaw = $request->input('selected_cart_item_ids', '');
    if (is_array($selectedRaw)) {
        $selectedIds = array_map('intval', $selectedRaw);
    } else {
        $selectedIds = array_filter(array_map('intval', explode(',', $selectedRaw)));
    }

    if (count($selectedIds) > 0) {
        $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedIds)
            ->get();
    } else {
        $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->get();
    }

    if ($cartItems->isEmpty()) {
        return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    $shippingMethods = \App\Models\admin\ShippingMethod::whereIn('id', [1, 2])->where('active', 1)->get();
    $shippingMethodId = session('shipping_method', 1);
    $shippingMethod   = $shippingMethods->firstWhere('id', $shippingMethodId);

    $subtotal = $cartItems->sum(function ($item) {
        return ($item->price ?? 0) * ($item->quantity ?? 1);
    });

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

    $shippingCost = $freeShippingCode ? 0 : ($shippingMethod ? $shippingMethod->cost : 0);
    $total = $subtotal + $shippingCost - $discountAmount;

    $appliedDiscountCodes = collect();
    if ($orderDiscountCode) $appliedDiscountCodes->push($orderDiscountCode);
    if ($freeShippingCode)  $appliedDiscountCodes->push($freeShippingCode);

    return view('frontend.checkout.checkout', [
        'addresses'           => $addresses,
        'address'             => $address,
        'cartItems'           => $cartItems,
        'shippingMethods'     => $shippingMethods,
        'shippingMethodId'    => $shippingMethodId,
        'shippingMethod'      => $shippingMethod,
        'subtotal'            => $subtotal,
        'shippingCost'        => $shippingCost,
        'discountAmount'      => $discountAmount,
        'total'               => $total,
        'validDiscountCodes'  => \App\Models\admin\DiscountCode::where('active', 1)
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
        'momoResult'          => null,
    ]);
}




    /**
     * Xử lý đặt hàng (POST)
     */
public function processOrder(Request $request)
{
    $user = auth()->user();

    // Validate địa chỉ giao hàng
    $validatedAddress = $request->validate([
        'address_id'      => 'nullable|exists:addresses,id',
        'recipient_name'  => 'required|string|max:100',
        'phone'           => 'required|string|max:20',
        'province'        => 'required|string|max:100',
        'district'        => 'required|string|max:100',
        'ward'            => 'required|string|max:100',
        'address'         => 'required|string',
    ]);

    // Cập nhật hoặc tạo địa chỉ mặc định
    if ($request->filled('address_id')) {
        $address = $user->addresses()->findOrFail($request->input('address_id'));
        $address->update($validatedAddress);
    } else {
        $address = $user->addresses()->create($validatedAddress);
    }
    $address->update(['is_default' => true]);
    $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

    $shipping_method = $request->input('shipping_method', 1);
    $payment_method = $request->input('payment_method', 'cod');

    // Lấy cart item id đã chọn
    $selectedIds = $request->input('selected_cart_item_ids', []);
    if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
    $selectedIds = array_map('intval', $selectedIds);

    $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
        ->where('user_id', $user->id)
        ->when(count($selectedIds) > 0, function ($query) use ($selectedIds) {
            $query->whereIn('id', $selectedIds);
        })
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng trống!');
    }

    // KIỂM TRA TỒN KHO LẠI LẦN CUỐI (ĐƠN GIẢN, KHÔNG CẦN THÔNG BÁO CHI TIẾT)
    foreach ($cartItems as $item) {
        $stock = $item->variant_id
            ? (\App\Models\admin\ProductVariant::find($item->variant_id)->stock ?? 0)
            : (\App\Models\admin\Product::find($item->product_id)->stock ?? 0);
        if ($stock < $item->quantity) {
            return redirect()->route('client.cart.index')->with('error', 'Có sản phẩm không đủ số lượng tồn kho.');
        }
    }

    // ...Phần còn lại giữ nguyên...

    $shippingMethod = \App\Models\admin\ShippingMethod::find($shipping_method);
    $originalShippingCost = $shippingMethod ? $shippingMethod->cost : 0;

    $orderDiscountCodeStr = session('order_discount_code');
    $freeShippingCodeStr  = session('free_shipping_code');
    $orderDiscountCode = null;
    $freeShippingCode  = null;
    if ($orderDiscountCodeStr) {
        $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
            ->where('type', '!=', 'free_shipping')
            ->where('active', 1)
            ->first();
    }
    if ($freeShippingCodeStr) {
        $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
            ->where('type', 'free_shipping')
            ->where('active', 1)
            ->first();
    }

    $subtotal = $cartItems->sum(function ($item) {
        return ($item->price ?? 0) * ($item->quantity ?? 1);
    });

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

    $shippingCost = $freeShippingCode ? 0 : $originalShippingCost;
    $total = $subtotal + $shippingCost - $discountAmount;
    $status = $payment_method === 'momo' ? 'confirmed' : 'pending';

    $order = \App\Models\admin\Order::create([
        'user_id'               => $user->id,
        'recipient_name'        => $address->recipient_name,
        'phone'                 => $address->phone,
        'full_address'          => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
        'shipping_method' => $shippingMethod->name,
        'payment_method'        => $payment_method,
        'discount_code' => $orderDiscountCode ? $orderDiscountCode->code : null,
        'free_shipping_code' => $freeShippingCode ? $freeShippingCode->code : null,

        'discount_amount'       => $discountAmount,
        'total_amount'          => $total,
        'shipping_cost'         => $shippingCost,
        'status'                => $status,
        'payment_status'        => $payment_method === 'momo' ? 'paid' : 'unpaid',
    ]);

    foreach ($cartItems as $item) {
        $order->items()->create([
            'product_id'                => $item->product_id,
            'product_name'              => $item->product->name ?? '',
            'product_variant_value_id'  => $item->variant_id,
            'product_variant_value_name'=> $item->variant->name ?? null,
            'product_sku'               => $item->product->sku ?? null,
            'product_image'             => $item->product->image ?? null,
            'quantity'                  => $item->quantity,
            'price'                     => $item->price ?? 0,
            'total'                     => ($item->price ?? 0) * ($item->quantity ?? 1),
        ]);
        // Trừ kho
        if ($item->variant_id) {
            $variant = \App\Models\admin\ProductVariant::find($item->variant_id);
            if ($variant) {
                $variant->stock = max(0, $variant->stock - $item->quantity);
                $variant->save();
            }
        } else {
            $product = \App\Models\admin\Product::find($item->product_id);
            if ($product) {
                $product->stock = max(0, $product->stock - $item->quantity);
                $product->save();
            }
        }
    }

    // Xoá các cart item đã đặt hàng
    \App\Models\Client\CartItem::where('user_id', $user->id)
        ->when(count($selectedIds) > 0, function ($query) use ($selectedIds) {
            $query->whereIn('id', $selectedIds);
        })
        ->delete();

    session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method']);

    return redirect()->route('client.checkout.success');
}



    /**
     * Cập nhật phương thức vận chuyển (AJAX)
     */
    public function updateShippingMethod(Request $request)
    {

        $shippingMethods = ShippingMethod::whereIn('id', [1, 2])  // hoặc lấy tất cả
            ->where('active', 1)
            ->get();
        $shippingMethodId = $request->input('shipping_method', 1);
        $orderDiscountCodeStr = $request->input('order_discount_code', null);
        $freeShippingCodeStr  = $request->input('free_shipping_code', null);

        // Lưu session
        session([
            'shipping_method'    => $shippingMethodId,
            'order_discount_code'   => $orderDiscountCodeStr,
            'free_shipping_code'    => $freeShippingCodeStr,
        ]);

        $user = auth()->user();
        $shippingMethod = ShippingMethod::find($shippingMethodId);

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


        $subtotal = $cartItems->sum(function ($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 1);
        });

        // Lấy discount code đúng type
        $orderDiscountCode = null;
        $freeShippingCode  = null;

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

        // Tính giảm giá
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

        $shippingCost = $freeShippingCode ? 0 : ($shippingMethod ? $shippingMethod->cost : 0);
        $total = $subtotal + $shippingCost - $discountAmount;

        return response()->json([
            'shipping_cost'   => (int) $shippingCost,
            'total'           => (int) $total,
            'discount_amount' => (int) $discountAmount,
            'subtotal'        => (int) $subtotal,

        ]);
    }

    /**
     * Áp mã giảm giá hoặc freeship (AJAX)
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'order_discount_code'  => 'nullable|string',
            'free_shipping_code'   => 'nullable|string',
        ]);

        $orderCodeInput = strtoupper(trim($request->input('order_discount_code', '')));
        $shippingCodeInput = strtoupper(trim($request->input('free_shipping_code', '')));

        // Nếu không có mã nào được chọn → xóa session và trả về
        if (empty($orderCodeInput) && empty($shippingCodeInput)) {
            session()->forget(['order_discount_code', 'free_shipping_code']);
            return response()->json(['success' => true]);
        }

        if ($request->filled('free_shipping_code')) {
            $codesInput[] = strtoupper(trim($request->input('free_shipping_code')));
        }

        if (empty($codesInput)) {
            return response()->json(['success' => false, 'message' => 'Chưa chọn mã nào']);
        }

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
            ->get();

        if ($validCodes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có mã hợp lệ']);
        }


        // Lưu session đúng loại mã
        $orderCode    = $validCodes->firstWhere('code', strtoupper($request->input('order_discount_code')));
        $shippingCode = $validCodes->firstWhere('code', strtoupper($request->input('free_shipping_code')));

        if ($orderCode && $orderCode->type !== 'order_discount') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đơn hàng không hợp lệ']);
        }
        if ($shippingCode && $shippingCode->type !== 'free_shipping') {
            return response()->json(['success' => false, 'message' => 'Mã miễn phí vận chuyển không hợp lệ']);
        }



        session([
            'order_discount_code'   => $orderCode ? $orderCode->code : null,
            'free_shipping_code'    => $shippingCode ? $shippingCode->code : null,
        ]);


        return response()->json(['success' => true]);
    }


    /**
     * Xoá mã giảm giá (AJAX)
     */

    public function removeDiscount(Request $request)
    {
        session()->forget(['order_discount_code', 'free_shipping_code']);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('client.checkout')->with('success', 'Đã xoá mã giảm giá!');
    }

    /**
     * Trả về mảng [orderDiscountCode, freeShippingCode] (private helper)
     */
    private function resolveDiscountCodes(): array
    {
        $orderCodeStr    = session('order_discount_code');
        $freeShipCodeStr = session('free_shipping_code');
        $codes = array_filter([$orderCodeStr, $freeShipCodeStr]);

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
//     public function updatePendingPaymentAddress(Request $request)
// {
//     $user = auth()->user();
//     $orderId = $request->input('momo_order_id') ?? session('pending_momo_payment.orderId');
//     if (!$orderId) return response()->json(['error' => 'Không xác định đơn MoMo'], 400);

//     $pendingPayment = \App\Models\Client\PendingPayment::where('user_id', $user->id)
//         ->where('status', 'paid')
//         ->where('order_id', $orderId)
//         ->first();

//     if (!$pendingPayment) return response()->json(['error' => 'Không tìm thấy pending payment'], 404);

//     $pendingPayment->recipient_name = $request->input('recipient_name');
//     $pendingPayment->phone = $request->input('phone');
//     $pendingPayment->full_address =
//         $request->input('address') . ', ' .
//         $request->input('ward') . ', ' .
//         $request->input('district') . ', ' .
//         $request->input('province');
//     $pendingPayment->save();

//     return response()->json(['success' => true]);
// }

}
