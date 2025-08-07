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
use App\Models\admin\DiscountCodeUsage;
use Illuminate\Support\Facades\DB;


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

    // Check callback MoMo (nếu có, thì đặt đơn luôn và chuyển sang trang thành công)
$isMomoCallback = $request->has('orderId') && $request->has('resultCode');
if ($isMomoCallback && $request->input('resultCode') == 0) {
            \Log::info('Session in MoMo callback:', session()->all());
    \Log::info('MoMo callback success, creating order...');
    
    // ĐẶT TOÀN BỘ LOGIC TẠO ĐƠN HÀNG Ở ĐÂY!
    $user = auth()->user();
    // Lấy lại các thông tin đã lưu trong session
    $selectedIds = session('momo_selected_cart_item_ids', []);
    if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
    $selectedIds = array_filter(array_map('intval', $selectedIds));
    $addressId = session('address_id');
    $address = $addressId ? $user->addresses()->where('id', $addressId)->first() : null;

    // Nếu không có address_id mà có session('address_snapshot'), tạo mới địa chỉ
    if (!$address && session('address_snapshot')) {
        $snapshot = session('address_snapshot');
        $address = $user->addresses()->create([
            'recipient_name' => $snapshot['recipient_name'],
            'phone'          => $snapshot['phone'],
            'address'        => $snapshot['address'],
            'province'       => $snapshot['province'],
            'district'       => $snapshot['district'],
            'ward'           => $snapshot['ward'],
            'is_default'     => true,
        ]);
        session()->forget('address_snapshot');
        $addressId = $address->id;
        session(['address_id' => $addressId]);
    }

    if (!$address) {
        return redirect()->route('client.checkout')->with('error', 'Bạn cần nhập địa chỉ giao hàng!');
    }

    $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
        ->where('user_id', $user->id)
        ->whereIn('id', $selectedIds)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống hoặc sản phẩm không hợp lệ!');
    }

    // ...Các bước lấy discount code, shipping method, tính toán, giống như trong processOrder...

    // Ví dụ:
$shippingMethodId = session('shipping_method', 1);
$shippingMethod = \App\Models\admin\ShippingMethod::find($shippingMethodId);

// Lấy tên hoặc id tỉnh/thành từ address
$province = $address->province ?? null;

// Lấy phí ship động theo tỉnh và phương thức:
$shippingCost = $this->getShippingCostByProvince($province, $shippingMethodId);


    $orderDiscountCodeStr = session('order_discount_code');
    $freeShippingCodeStr  = session('free_shipping_code');
    $orderDiscountCode = null;
    $freeShippingCode  = null;
if ($orderDiscountCodeStr) {
    $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
        ->where('type', '!=', 'free_shipping')
        ->where('active', 1)
        ->lockForUpdate() // PHẢI thêm dòng này!
        ->first();
}
if ($freeShippingCodeStr) {
    $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
        ->where('type', 'free_shipping')
        ->where('active', 1)
        ->lockForUpdate()
        ->first();
}
if (
    $orderDiscountCode &&
    $orderDiscountCode->scope === 'global' &&
    $orderDiscountCode->used_count >= $orderDiscountCode->usage_limit
) {
    return redirect()->route('client.checkout')->with('error', 'Mã giảm giá đã hết lượt sử dụng!');
}
if (
    $freeShippingCode &&
    $freeShippingCode->scope === 'global' &&
    $freeShippingCode->used_count >= $freeShippingCode->usage_limit
) {
    return redirect()->route('client.checkout')->with('error', 'Mã miễn phí vận chuyển đã hết lượt sử dụng!');
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
    'user_id'            => $user->id,
    'recipient_name'     => $address->recipient_name,
    'phone'              => $address->phone,
    'full_address'       => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
    'shipping_method'    => $shippingMethod ? $shippingMethod->name : null,
    'payment_method'     => 'momo',
    'discount_code'      => $orderDiscountCode ? $orderDiscountCode->code : null,        // <-- ĐÚNG
    'free_shipping_code' => $freeShippingCode ? $freeShippingCode->code : null,          // <-- ĐÚNG
    'discount_amount'    => $discountAmount,
    'total_amount'       => $total,
    'shipping_cost'      => $shippingCost,
    'status'             => 'confirmed',
    'payment_status'     => 'paid',
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
        // Trừ kho...
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
    if ($orderDiscountCode && $orderDiscountCode->scope === 'global') {
    $orderDiscountCode->increment('used_count');
}
if ($freeShippingCode && $freeShippingCode->scope === 'global') {
    $freeShippingCode->increment('used_count');
}
  $order->loadMissing('user');
            if ($order->user && $order->user->email) {
                try {
                    \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
                } catch (\Throwable $e) {
                    \Log::warning('Không gửi được mail trạng thái đơn hàng', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }


    // Xoá cart và session các thông tin đặt hàng
    \App\Models\Client\CartItem::where('user_id', $user->id)->whereIn('id', $selectedIds)->delete();
    session()->forget(['momo_selected_cart_item_ids', 'order_discount_code', 'free_shipping_code', 'shipping_method', 'address_id']);

    // Sau khi tạo order xong, redirect về success
    return redirect()->route('client.checkout.success');
}

    // Nếu không phải callback MoMo thì hiển thị trang checkout bình thường

    // Xóa mã giảm giá nếu không có sản phẩm chọn
    if (!$request->has('selected_cart_item_ids') && !$isMomoCallback) {
        session()->forget(['order_discount_code', 'free_shipping_code']);
    }

    // Lấy mã giảm giá từ session (áp dụng trên giao diện)
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

    // Lấy địa chỉ giao hàng mặc định
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
    $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
        ->where('user_id', $user->id)
        ->when(count($selectedIds) > 0, function ($query) use ($selectedIds) {
            $query->whereIn('id', $selectedIds);
        })
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    // Phương thức giao hàng
    $shippingMethodId = session('shipping_method');
    $shippingMethods = \App\Models\admin\ShippingMethod::where('active', 1)->get();
    if (!$shippingMethods->pluck('id')->contains($shippingMethodId)) {
        $shippingMethodId = optional($shippingMethods->first())->id;
        session(['shipping_method' => $shippingMethodId]);
    }
    $shippingMethod = $shippingMethods->firstWhere('id', $shippingMethodId);

    // Tính tổng tiền hàng
    $subtotal = $cartItems->sum(function ($item) {
        return ($item->price ?? 0) * ($item->quantity ?? 1);
    });

    // =========================
    // FILTER COUPON CHUẨN LOGIC
    // =========================
$now = now();
$validDiscountCodes = \App\Models\admin\DiscountCode::where('active', 1)
    ->where(function ($query) use ($now) {
        $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
    })
    ->where(function ($query) use ($now) {
        $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
    })
    ->where(function ($query) {
        $query->where('scope', 'conditional')
            ->orWhere(function ($q2) {
                $q2->where('scope', 'global')
                    ->whereNotNull('usage_limit')
                    ->whereRaw('used_count < usage_limit');
            });
    })
    ->get()
->filter(function ($code) use ($subtotal, $user) {
    $singleUseConditionTypes = ['new_user_30d', 'first_order'];

    // FIRST ORDER
    if ($code->scope === 'conditional' && $code->condition_type === 'first_order') {
        $hasOrder = \App\Models\admin\Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->exists();
        if ($hasOrder) return false;
        if ($code->min_order_amount && $subtotal < $code->min_order_amount) return false;
        return true;
    }

    // Các loại conditional khác (new_user_30d...)
    if ($code->scope === 'conditional' && in_array($code->condition_type, $singleUseConditionTypes)) {
        $alreadyUsed = \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $code->id)
            ->where('user_id', $user->id)
            ->exists();
        if ($alreadyUsed) return false;
        if ($code->condition_type === 'new_user_30d') {
            $days = $user->created_at->diffInDays(now());
            if ($days >= 30) return false;
        }
        if ($code->min_order_amount && $subtotal < $code->min_order_amount) return false;
        return true;
    }

    // GLOBAL COUPON: CẦN THÊM ĐIỀU KIỆN NÀY!
    if ($code->scope === 'global') {
        // Check đã dùng chưa (user đã đặt đơn - chưa bị hủy - dùng mã này)
        $alreadyUsed = \App\Models\admin\Order::where('user_id', $user->id)
            ->where(function($q) use ($code) {
                $q->where('discount_code', $code->code)
                  ->orWhere('free_shipping_code', $code->code);
            })
            ->where('status', '!=', 'cancelled')
            ->exists();
        if ($alreadyUsed) return false;

        // Check tổng lượt dùng toàn hệ thống, đã hết chưa
        if ($code->usage_limit && $code->used_count >= $code->usage_limit) return false;
        if ($code->min_order_amount && $subtotal < $code->min_order_amount) return false;
        return true;
    }

    // Các loại mã khác nếu có
    return !$code->min_order_amount || $subtotal >= $code->min_order_amount;
});


    // =========================
    // END FILTER COUPON
    // =========================
    // Tính tiền giảm giá (nếu có)

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
$province = $address->province ?? null; // Địa chỉ user đã chọn
$shippingCost = $freeShippingCode ? 0 : $this->getShippingCostByProvince($province, $shippingMethodId);
$total = $subtotal + $shippingCost - $discountAmount;
    // Coupon đang được áp dụng (nếu có)
    $appliedDiscountCodes = collect();
    if ($orderDiscountCode) $appliedDiscountCodes->push($orderDiscountCode);
    if ($freeShippingCode)  $appliedDiscountCodes->push($freeShippingCode);

    return view('frontend.checkout.checkout', [
        'addresses'             => $addresses,
        'address'               => $address,
        'cartItems'             => $cartItems,
        'shippingMethods'       => $shippingMethods,
        'shippingMethodId'      => $shippingMethodId,
        'shippingMethod'        => $shippingMethod,
        'subtotal'              => $subtotal,
        'shippingCost'          => $shippingCost,
        'discountAmount'        => $discountAmount,
        'total'                 => $total,
        'validDiscountCodes'    => $validDiscountCodes,
        'appliedDiscountCodes'  => $appliedDiscountCodes,
        'selected_cart_item_ids'=> $selectedIds,
        'momoResult'            => null,
    ]);
}
    /**
     * Xử lý đặt hàng (POST)
     */

public function processOrder(Request $request)
{
    try {
        return \DB::transaction(function () use ($request) {
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

            // Cập nhật/tạo địa chỉ mặc định
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
                throw new \Exception('Giỏ hàng trống!');
            }

            // Kiểm tra tồn kho
foreach ($cartItems as $item) {
    if ($item->variant_id) {
        $variant = \App\Models\admin\ProductVariant::find($item->variant_id);
        $stock = $variant ? $variant->stock : 0;
        $productName = $item->product->name ?? '';
        $variantName = $variant->name ?? '';
        if ($stock < $item->quantity) {
            throw new \Exception('Sản phẩm "' . $productName . ' - ' . $variantName . '" hiện chỉ còn ' . $stock . ' sản phẩm.');
        }
    } else {
        $product = \App\Models\admin\Product::find($item->product_id);
        $stock = $product ? $product->stock : 0;
        $productName = $item->product->name ?? '';
        if ($stock < $item->quantity) {
            throw new \Exception('Sản phẩm "' . $productName . '" hiện chỉ còn ' . $stock . ' sản phẩm.');
        }
    }
}


$shippingMethod = \App\Models\admin\ShippingMethod::find($shipping_method);
// Lấy tỉnh/thành từ address đã validate
$province = $address->province ?? $request->input('province');

            // ==== PHẦN QUAN TRỌNG: LẤY MÃ VÀ LOCK ROW ====
            $orderDiscountCodeStr = session('order_discount_code');
            $freeShippingCodeStr  = session('free_shipping_code');
            $orderDiscountCode = null;
            $freeShippingCode  = null;

            if ($orderDiscountCodeStr) {
                $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
                    ->where('type', '!=', 'free_shipping')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();
            }
            if ($freeShippingCodeStr) {
                $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
                    ->where('type', 'free_shipping')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();
            }

            // Tính tổng tiền hàng
            $subtotal = $cartItems->sum(function ($item) {
                return ($item->price ?? 0) * ($item->quantity ?? 1);
            });

            // Kiểm tra điều kiện min_order_amount
            if ($orderDiscountCode && $orderDiscountCode->min_order_amount && $subtotal < $orderDiscountCode->min_order_amount) {
                throw new \Exception('Đơn hàng chưa đủ giá trị tối thiểu để áp dụng mã giảm giá.');
            }

            // ==== KIỂM TRA LẠI USED_COUNT NGAY TRONG TRANSACTION ====
            // Chỉ check used_count cho mã global!
            if (
                $orderDiscountCode &&
                $orderDiscountCode->scope === 'global' &&
                $orderDiscountCode->used_count >= $orderDiscountCode->usage_limit
            ) {
                throw new \Exception('Mã giảm giá đã hết lượt sử dụng!');
            }
            if (
                $freeShippingCode &&
                $freeShippingCode->scope === 'global' &&
                $freeShippingCode->used_count >= $freeShippingCode->usage_limit
            ) {
                throw new \Exception('Mã miễn phí vận chuyển đã hết lượt sử dụng!');
            }

            // Tính tiền giảm
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

       $shippingCost = $freeShippingCode ? 0 : $this->getShippingCostByProvince($province, $shipping_method);
            $total = $subtotal + $shippingCost - $discountAmount;
            $status = $payment_method === 'momo' ? 'confirmed' : 'pending';

            // ==== TẠO ĐƠN HÀNG ====
$order = \App\Models\admin\Order::create([
    'user_id'           => $user->id,
    'recipient_name'    => $address->recipient_name,
    'phone'             => $address->phone,
    'full_address'      => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
    'shipping_method'   => $shippingMethod->name,
    'payment_method'    => $payment_method,
    'discount_code'     => $orderDiscountCode ? $orderDiscountCode->code : null,
    'free_shipping_code'=> $freeShippingCode ? $freeShippingCode->code : null,
    'discount_amount'   => $discountAmount,
    'shipping_cost'     => $shippingCost,
    'total_amount'      => $total,
    'status'            => $status,
    'payment_status'    => $payment_method === 'momo' ? 'paid' : 'unpaid',
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

            // ==== TĂNG USED_COUNT (SAU KHI ĐẶT ĐƠN) ====
            if ($orderDiscountCode && $orderDiscountCode->scope === 'global') {
                $orderDiscountCode->increment('used_count');
            }
            if ($freeShippingCode && $freeShippingCode->scope === 'global') {
                $freeShippingCode->increment('used_count');
            }

            // ==== LƯU DiscountCodeUsage cho conditional 1 lần/user ====
$singleUseConditionTypes = ['new_user_30d', 'first_order'];

if (
    $orderDiscountCode &&
    $orderDiscountCode->scope === 'conditional' &&
    in_array($orderDiscountCode->condition_type, $singleUseConditionTypes)
) {
    \App\Models\admin\DiscountCodeUsage::firstOrCreate([
        'discount_code_id' => $orderDiscountCode->id,
        'user_id' => $user->id,
        'order_id' => $order->id,
        'used_at' => now(),
    ]);
}
if (
    $freeShippingCode &&
    $freeShippingCode->scope === 'conditional' &&
    in_array($freeShippingCode->condition_type, $singleUseConditionTypes)
) {
    \App\Models\admin\DiscountCodeUsage::firstOrCreate([
        'discount_code_id' => $freeShippingCode->id,
        'user_id' => $user->id,
        'order_id' => $order->id,
        'used_at' => now(),
    ]);
}


            // ==== Gửi mail nếu có ====
            $order->loadMissing('user');
            if ($order->user && $order->user->email) {
                try {
                    \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
                } catch (\Throwable $e) {
                    \Log::warning('Không gửi được mail trạng thái đơn hàng', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Xoá giỏ hàng đã đặt
            \App\Models\Client\CartItem::where('user_id', $user->id)
                ->when(count($selectedIds) > 0, function ($query) use ($selectedIds) {
                    $query->whereIn('id', $selectedIds);
                })
                ->delete();

            session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method']);

            // Thành công
            return redirect()->route('client.checkout.success');
        });
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}





    /**
     * Cập nhật phương thức vận chuyển (AJAX)
     */
    public function updateShippingMethod(Request $request)
{
    $shippingMethodId     = $request->input('shipping_method', 1);

    // Lấy giá trị hiện tại từ session (phòng trường hợp client không truyền lên)
    $currentOrderDiscountCode = session('order_discount_code');
    $currentFreeShippingCode  = session('free_shipping_code');

    // Lấy giá trị mới từ request (có thể null hoặc rỗng nếu client không truyền lên)
    $orderDiscountCodeStr = $request->input('order_discount_code', $currentOrderDiscountCode);
    $freeShippingCodeStr  = $request->input('free_shipping_code', $currentFreeShippingCode);

    // Lưu lại session, nếu không truyền lên thì giữ nguyên session cũ
    session([
        'shipping_method'      => $shippingMethodId,
        'order_discount_code'  => $orderDiscountCodeStr,
        'free_shipping_code'   => $freeShippingCodeStr,
    ]);

    $user = auth()->user();
    $shippingMethod = ShippingMethod::find($shippingMethodId);

    // Lấy các cart item được chọn
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

    // Lấy discount code từ DB (nếu có)
    $orderDiscountCode = null;
    $freeShippingCode  = null;

    if (!empty($orderDiscountCodeStr)) {
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

        // Nếu không đủ điều kiện tối thiểu thì hủy áp mã
        if ($orderDiscountCode && $orderDiscountCode->min_order_amount && $subtotal < $orderDiscountCode->min_order_amount) {
            $orderDiscountCode = null;
        }
    }

    if (!empty($freeShippingCodeStr)) {
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
$province = $request->input('province');
$shippingCost = $freeShippingCode ? 0 : $this->getShippingCostByProvince($province, $shippingMethodId);
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

    $orderCodeInput    = strtoupper(trim($request->input('order_discount_code', '')));
    $shippingCodeInput = strtoupper(trim($request->input('free_shipping_code', '')));

    // Nếu không có mã nào được chọn → xóa session
    if (empty($orderCodeInput) && empty($shippingCodeInput)) {
        session()->forget(['order_discount_code', 'free_shipping_code']);
        return response()->json(['success' => true]);
    }

    $codesInput = array_filter([$orderCodeInput, $shippingCodeInput]);

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

    $orderCode    = $validCodes->firstWhere('code', $orderCodeInput);
    $shippingCode = $validCodes->firstWhere('code', $shippingCodeInput);

    if ($orderCode && $orderCode->type !== 'order_discount') {
        return response()->json(['success' => false, 'message' => 'Mã giảm giá đơn hàng không hợp lệ']);
    }

    if ($shippingCode && $shippingCode->type !== 'free_shipping') {
        return response()->json(['success' => false, 'message' => 'Mã miễn phí vận chuyển không hợp lệ']);
    }

    // ✅ Kiểm tra min_order_amount của mã order_discount
    if ($orderCode) {
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)->get();
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 1);
        });

        if ($orderCode->min_order_amount && $subtotal < $orderCode->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đủ giá trị tối thiểu để áp dụng mã giảm giá.'
            ]);
        }
    }

    // Lưu session
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
public function checkDiscountBeforeMomo(Request $request)
{
    $orderDiscountCodeStr = $request->input('order_discount_code');
    $freeShippingCodeStr  = $request->input('free_shipping_code');

    $orderDiscountCode = null;
    $freeShippingCode  = null;

    // Lấy discount code (chỉ cần check nhanh ở đây, lock ở callback MoMo vẫn phải có)
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

    // Kiểm tra tồn kho mã
    if (
        $orderDiscountCode &&
        $orderDiscountCode->scope === 'global' &&
        $orderDiscountCode->used_count >= $orderDiscountCode->usage_limit
    ) {
        return response()->json(['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng!']);
    }
    if (
        $freeShippingCode &&
        $freeShippingCode->scope === 'global' &&
        $freeShippingCode->used_count >= $freeShippingCode->usage_limit
    ) {
        return response()->json(['valid' => false, 'message' => 'Mã miễn phí vận chuyển đã hết lượt sử dụng!']);
    }

    // Nếu còn lượt dùng
    return response()->json(['valid' => true]);
}

private function getShippingCostByProvince($province, $shippingMethodId)
{
    $path = public_path('data/ship_cost_by_province.json');
    if (!file_exists($path)) return 0;

    $json = json_decode(file_get_contents($path), true);
    if (!$json) return 0;

    foreach ($json as $item) {
        if (
            $item['province_id'] == $province ||
            $item['province_name'] == $province
        ) {
            foreach ($item['shipping_methods'] as $method) {
                if ($method['id'] == $shippingMethodId) {
                    return $method['cost'];
                }
            }
        }
    }
    return 0;
}


}
