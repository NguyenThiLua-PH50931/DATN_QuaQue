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
    $addresses = $user ? $user->addresses()->get() : collect();

    // Kiểm tra callback từ MoMo (nếu có)
    $isMomoCallback = $request->has('orderId') && $request->has('resultCode');
    if (!$request->has('selected_cart_item_ids') && !$isMomoCallback) {
        // Nếu không chọn sản phẩm thì clear session giảm giá
        session()->forget(['order_discount_code', 'free_shipping_code']);
    }

    // Lấy mã giảm giá từ session hoặc pending_payment (nếu vừa thanh toán momo)
    $orderDiscountCodeStr = session('order_discount_code');
    $freeShippingCodeStr  = session('free_shipping_code');

    // Nếu là callback MoMo thành công, ưu tiên lấy mã từ pending_payment
    if ($isMomoCallback) {
        $pending = \App\Models\Client\PendingPayment::where('order_id', $request->input('orderId'))->first();
        if ($pending) {
            if (!$orderDiscountCodeStr && $pending->discount_code_id) {
                $discountCode = \App\Models\admin\DiscountCode::find($pending->discount_code_id);
                if ($discountCode) {
                    $orderDiscountCodeStr = $discountCode->code;
                    session(['order_discount_code' => $discountCode->code]);
                }
            }
            if (!$freeShippingCodeStr && $pending->free_shipping_code_id) {
                $freeShippingCode = \App\Models\admin\DiscountCode::find($pending->free_shipping_code_id);
                if ($freeShippingCode) {
                    $freeShippingCodeStr = $freeShippingCode->code;
                    session(['free_shipping_code' => $freeShippingCode->code]);
                }
            }
        }
    }

    // Địa chỉ giao hàng mặc định
    $addressId = session('address_id');
    $address = $addressId
        ? $user->addresses()->where('id', $addressId)->first()
        : $user->addresses()->where('is_default', 1)->first();

    // Lấy các cart item được chọn (ưu tiên theo id selected, fallback toàn bộ cart)
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

    // Phương thức vận chuyển
    $shippingMethods = ShippingMethod::whereIn('id', [1, 2])->where('active', 1)->get();
    $shippingMethodId = session('shipping_method_id', 1);
    $shippingMethod   = $shippingMethods->firstWhere('id', $shippingMethodId);

    // Lấy đối tượng DiscountCode
    $orderDiscountCode = null;
    $freeShippingCode  = null;

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

    // Tính tiền
    $subtotal = $cartItems->sum(function ($item) {
        return ($item->price ?? 0) * ($item->quantity ?? 1);
    });

    $discountAmount = 0;
    if ($orderDiscountCode) {
        if ($orderDiscountCode->discount_type === 'percent') {
            $amount = $subtotal * ($orderDiscountCode->discount_value / 100);
            if ($orderDiscountCode->max_discount_amount) {
                $amount = min($amount, $orderDiscountCode->max_discount_amount);
            }
        } else {
            $amount = $orderDiscountCode->discount_value;
        }
        $discountAmount = min($amount, $subtotal);
    }

    $shippingCost = $freeShippingCode ? 0 : ($shippingMethod ? $shippingMethod->cost : 0);

    $total = $subtotal + $shippingCost - $discountAmount;

    // ===== Callback từ MoMo (nếu có) =====
    $momoResult = null;
    if ($request->has('resultCode')) {
        $momoResult = [
            'resultCode' => $request->input('resultCode'),
            'orderId'    => $request->input('orderId'),
            'message'    => $request->input('message'),
        ];

        if ($momoResult['resultCode'] == 0) {
            $orderId = $momoResult['orderId'];
            $selectedIds = session('momo_selected_cart_item_ids', []);

            // Lưu snapshot cart items lại vào pending payment nếu chưa tồn tại
            $cartItemsSnapshot = CartItem::with(['product', 'variant'])
                ->where('user_id', $user->id)
                ->whereIn('id', $selectedIds)
                ->get()
                ->map(function ($item) {
                    return [
                        'product_id'   => $item->product_id,
                        'variant_id'   => $item->variant_id,
                        'product_name' => $item->product->name ?? '',
                        'variant_name' => $item->variant->name ?? null,
                        'quantity'     => $item->quantity,
                        'price'        => $item->price,
                        'image'        => $item->product->image ?? null,
                        'sku'          => $item->variant->sku ?? $item->product->sku ?? null,
                        'total'        => ($item->price ?? 0) * ($item->quantity ?? 1),
                    ];
                })->toArray();

            $amount = $request->input('amount');

            $existing = \App\Models\Client\PendingPayment::where('order_id', $orderId)->first();
            if (!$existing && !empty($selectedIds) && !empty($cartItemsSnapshot)) {
                // Tạo pending MoMo mới
                \App\Models\Client\PendingPayment::create([
                    'user_id' => $user->id,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'cart_item_ids' => $selectedIds,
                    'cart_items_snapshot' => $cartItemsSnapshot,
                    'payment_method' => 'momo',
                    'status' => 'paid',
                    // Thông tin giao nhận:
                    'recipient_name' => $address->recipient_name ?? null,
                    'phone' => $address->phone ?? null,
                    'full_address' => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
                    'shipping_method_id' => $shippingMethodId,
                    'shipping_cost' => $shippingCost,
                    'discount_code_id' => $orderDiscountCode ? $orderDiscountCode->id : null,
                    'free_shipping_code_id' => $freeShippingCode ? $freeShippingCode->id : null,
                    'discount_amount' => $discountAmount,
                ]);

                // === TRỪ KHO NGAY KHI THANH TOÁN MOMO THÀNH CÔNG ===
                foreach ($cartItemsSnapshot as $item) {
                    if (!empty($item['variant_id'])) {
                        $variant = \App\Models\Client\ProductVariant::find($item['variant_id']);
                        if ($variant) {
                            $variant->stock = max(0, $variant->stock - $item['quantity']);
                            $variant->save();
                        }
                    } else {
                        $product = \App\Models\Client\Product::find($item['product_id']);
                        if ($product && isset($product->stock)) {
                            $product->stock = max(0, $product->stock - $item['quantity']);
                            $product->save();
                        }
                    }
                }
            }

            session()->put('pending_momo_payment', [
                'orderId' => $orderId,
                'amount' => $amount,
                'cart_item_ids' => $selectedIds,
                'timestamp' => now(),
            ]);
        }
    }

    // Chuẩn bị dữ liệu cho view
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
        'validDiscountCodes'  => DiscountCode::where('active', 1)
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
        'momoResult'          => $momoResult,
    ]);
}


    /**
     * Xử lý đặt hàng (POST)
     */
    public function processOrder(Request $request)
{
    $user = Auth::user();

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

    // Cập nhật hoặc tạo địa chỉ mặc định cho user
    if ($request->filled('address_id')) {
        $address = $user->addresses()->findOrFail($request->input('address_id'));
        $address->update($validatedAddress);
    } else {
        $address = $user->addresses()->create($validatedAddress);
    }
    $address->update(['is_default' => true]);
    $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

    // Kiểm tra có pending momo payment không
    $momoOrderId = $request->input('momo_order_id') ?? session('pending_momo_payment.orderId');
    $pendingPayment = null;
    if ($momoOrderId) {
        $pendingPayment = \App\Models\Client\PendingPayment::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('order_id', $momoOrderId)
            ->first();
    }

    // ====== Xử lý ĐƠN HÀNG TỪ PENDING MOMO PAYMENT ======
    if ($pendingPayment) {
        // -- Cập nhật lại địa chỉ trong PendingPayment từ form mới nhất --
        $pendingPayment->recipient_name = $request->input('recipient_name');
        $pendingPayment->phone = $request->input('phone');
        $pendingPayment->full_address = $request->input('address') . ', ' . $request->input('ward') . ', ' . $request->input('district') . ', ' . $request->input('province');
        $pendingPayment->save();

        // -- Lấy các thông tin từ PendingPayment đã được cập nhật --
        $shipping_method_id = $pendingPayment->shipping_method_id;
        $shippingCost      = $pendingPayment->shipping_cost;
        $discountAmount    = $pendingPayment->discount_amount;
        $orderDiscountCode = $pendingPayment->discount_code_id ? \App\Models\admin\DiscountCode::find($pendingPayment->discount_code_id) : null;
        $freeShippingCode  = $pendingPayment->free_shipping_code_id ? \App\Models\admin\DiscountCode::find($pendingPayment->free_shipping_code_id) : null;
        $total             = $pendingPayment->amount;

        $status = 'confirmed';
        $order = \App\Models\admin\Order::create([
            'user_id'               => $user->id,
            'recipient_name'        => $pendingPayment->recipient_name,
            'phone'                 => $pendingPayment->phone,
            'full_address'          => $pendingPayment->full_address,
            'shipping_method_id'    => $shipping_method_id,
            'payment_method'        => 'momo',
            'discount_code_id'      => $orderDiscountCode ? $orderDiscountCode->id : null,
            'free_shipping_code_id' => $freeShippingCode ? $freeShippingCode->id : null,
            'discount_amount'       => $discountAmount,
            'total_amount'          => $total,
            'shipping_cost'         => $shippingCost,
            'status'                => $status,
            'payment_status'        => 'paid',
        ]);

        // -- Lưu các item vào order --
        foreach ($pendingPayment->cart_items_snapshot as $item) {
            $order->items()->create([
                'product_id'                => $item['product_id'],
                'product_name'              => $item['product_name'],
                'product_variant_value_id'  => $item['variant_id'] ?? null,
                'product_variant_value_name'=> $item['variant_name'] ?? null,
                'product_sku'               => $item['sku'] ?? null,
                'product_image'             => $item['image'] ?? null,
                'quantity'                  => $item['quantity'],
                'price'                     => $item['price'] ?? 0,
                'total'                     => $item['total'] ?? 0,
            ]);
            // KHÔNG trừ kho nữa (đã trừ lúc thanh toán MoMo thành công)
            // Xoá cart item nếu còn tồn tại
            \App\Models\Client\CartItem::where('user_id', $user->id)
                ->where('product_id', $item['product_id'])
                ->when($item['variant_id'] ?? null, function ($q) use ($item) {
                    $q->where('variant_id', $item['variant_id']);
                })
                ->delete();
        }

        // -- Cập nhật trạng thái PendingPayment --
        $pendingPayment->status = 'processed';
        $pendingPayment->order_id = $order->id;
        $pendingPayment->save();
        session()->forget('pending_momo_payment');
        session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method_id']);

        return redirect()->route('client.checkout.success');
    }

    // ====== ĐƠN HÀNG THƯỜNG (COD, BANK,...) ======
    $shipping_method_id = $request->input('shipping_method_id', 1);
    $payment_method = $request->input('payment_method', 'cod');

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
        return back()->with('error', 'Giỏ hàng trống!');
    }

    $shippingMethod = \App\Models\admin\ShippingMethod::find($shipping_method_id);
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
        'shipping_method_id'    => $shipping_method_id,
        'payment_method'        => $payment_method,
        'discount_code_id'      => $orderDiscountCode ? $orderDiscountCode->id : null,
        'free_shipping_code_id' => $freeShippingCode ? $freeShippingCode->id : null,
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
        // Trừ kho nếu là đơn thường
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

    session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method_id']);

    return redirect()->route('client.checkout.success');
}




    /**
     * Lưu/cập nhật địa chỉ giao hàng độc lập (AJAX)
     */
    public function saveAddress(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'address_id'      => 'nullable|exists:addresses,id',
            'recipient_name'  => 'required|string|max:100',
            'phone'           => 'required|string|max:20',
            'province'        => 'required|string|max:100',
            'district'        => 'required|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'address'         => 'required|string',
        ]);

        if ($request->filled('address_id')) {
            $address = $user->addresses()->findOrFail($request->input('address_id'));
            $address->update($validated);
        } else {
            $address = $user->addresses()->create($validated);
        }

        return redirect()->route('client.checkout')->with('success', 'Địa chỉ đã được lưu thành công.');
    }

    /**
     * Cập nhật phương thức vận chuyển (AJAX)
     */
    public function updateShippingMethod(Request $request)
    {

        $shippingMethods = ShippingMethod::whereIn('id', [1, 2])  // hoặc lấy tất cả
            ->where('active', 1)
            ->get();
        $shippingMethodId = $request->input('shipping_method_id', 1);
        $orderDiscountCodeStr = $request->input('order_discount_code', null);
        $freeShippingCodeStr  = $request->input('free_shipping_code', null);

        // Lưu session
        session([
            'shipping_method_id'    => $shippingMethodId,
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
    public function updatePendingPaymentAddress(Request $request)
{
    $user = auth()->user();
    $orderId = $request->input('momo_order_id') ?? session('pending_momo_payment.orderId');
    if (!$orderId) return response()->json(['error' => 'Không xác định đơn MoMo'], 400);

    $pendingPayment = \App\Models\Client\PendingPayment::where('user_id', $user->id)
        ->where('status', 'paid')
        ->where('order_id', $orderId)
        ->first();

    if (!$pendingPayment) return response()->json(['error' => 'Không tìm thấy pending payment'], 404);

    $pendingPayment->recipient_name = $request->input('recipient_name');
    $pendingPayment->phone = $request->input('phone');
    $pendingPayment->full_address =
        $request->input('address') . ', ' .
        $request->input('ward') . ', ' .
        $request->input('district') . ', ' .
        $request->input('province');
    $pendingPayment->save();

    return response()->json(['success' => true]);
}

}
