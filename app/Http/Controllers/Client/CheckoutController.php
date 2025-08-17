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

    $addresses = $user->addresses()->get();

    // ==== Flags từ callback/redirect ====
    $isMomoCallback = $request->has('orderId') && $request->has('resultCode');

    $appTransId = $request->input('app_trans_id')
        ?? $request->input('apptransid')
        ?? $request->input('appTransId');
    $isZLPRedirect = !empty($appTransId);

    // ZLP redirect success?
    $zlpStatus  = (string)($request->input('status') ?? $request->input('Status') ?? '');
    $zlpReturn  = (string)($request->input('return_code') ?? $request->input('returncode') ?? $request->input('returnCode') ?? '');
    $zlpSuccess = ($zlpStatus === '1') || ($zlpReturn === '1');

    /* ======================================================================
     |  MOMO CALLBACK (resultCode = 0)
     |======================================================================*/
    if ($isMomoCallback && (string)$request->input('resultCode') === '0') {
        \Log::info('MoMo callback success');

        $momoOrderId = (string) $request->input('orderId'); // orderId khi tạo thanh toán
        $momoTransId = (string) $request->input('transId'); // transId MoMo trả về

        // Lấy danh sách cart items đã chọn
        $selectedIds = session('momo_selected_cart_item_ids', []);
        if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
        $selectedIds = array_filter(array_map('intval', $selectedIds));

        // Địa chỉ: lấy id hoặc snapshot
        $addressId = session('address_id');
        $address   = $addressId ? $user->addresses()->where('id', $addressId)->first() : null;
        if (!$address && session('address_snapshot')) {
            $s = session('address_snapshot');
            $address = $user->addresses()->create([
                'recipient_name' => $s['recipient_name'],
                'phone'          => $s['phone'],
                'address'        => $s['address'],
                'province'       => $s['province'],
                'district'       => $s['district'],
                'ward'           => $s['ward'],
                'is_default'     => true,
            ]);
            session()->forget('address_snapshot');
            session(['address_id' => $address->id]);
        }
        if (!$address) return redirect()->route('client.checkout')->with('error', 'Bạn cần nhập địa chỉ giao hàng!');

        $cartItems = \App\Models\Client\CartItem::with(['product','variant'])
            ->where('user_id', $user->id)->whereIn('id', $selectedIds)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống hoặc sản phẩm không hợp lệ!');
        }

        // Phí ship
        $shippingMethodId = session('shipping_method', 1);
        $shippingMethod   = \App\Models\admin\ShippingMethod::find($shippingMethodId);
        $province         = $address->province ?? null;
        $shippingCostBase = $this->getShippingCostByProvince($province, $shippingMethodId);

        // Mã giảm giá (KHÓA record khi đọc)
        $orderDiscountCodeStr = session('order_discount_code');
        $freeShippingCodeStr  = session('free_shipping_code');
        $orderDiscountCode = null;
        $freeShippingCode  = null;
        if ($orderDiscountCodeStr) {
            $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
                ->where('type', '!=', 'free_shipping')->where('active', 1)->lockForUpdate()->first();
        }
        if ($freeShippingCodeStr) {
            $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
                ->where('type', 'free_shipping')->where('active', 1)->lockForUpdate()->first();
        }
        if ($orderDiscountCode && $orderDiscountCode->scope === 'global' && $orderDiscountCode->used_count >= $orderDiscountCode->usage_limit) {
            return redirect()->route('client.checkout')->with('error', 'Mã giảm giá đã hết lượt sử dụng!');
        }
        if ($freeShippingCode && $freeShippingCode->scope === 'global' && $freeShippingCode->used_count >= $freeShippingCode->usage_limit) {
            return redirect()->route('client.checkout')->with('error', 'Mã miễn phí vận chuyển đã hết lượt sử dụng!');
        }

        // Tính tiền
        $subtotal = $cartItems->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1));
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
        $shippingCost = $freeShippingCode ? 0 : $shippingCostBase;
        $total        = $subtotal + $shippingCost - $discountAmount;

        // Kéo transId từ cache IPN nếu cần
        if (!$momoTransId && $momoOrderId) {
            if ($tid = cache()->pull('momo:ipn:' . $momoOrderId)) {
                $momoTransId = $tid;
            }
        }

        // ===== Chặn oversell tại thời điểm xác nhận =====
        try {
            // 1) KHÓA + kiểm tra + TRỪ kho (FOR UPDATE) – nếu thiếu sẽ throw
            $this->consumeStockOrFail($cartItems);

            // 2) Tạo order + items trong 1 transaction (không trừ kho nữa)
            DB::transaction(function () use ($user, $address, $shippingMethod, $orderDiscountCode, $freeShippingCode, $discountAmount, $shippingCost, $total, $cartItems, $momoOrderId, $momoTransId) {
                $order = \App\Models\admin\Order::create([
                    'user_id'            => $user->id,
                    'recipient_name'     => $address->recipient_name,
                    'phone'              => $address->phone,
                    'full_address'       => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
                    'shipping_method'    => $shippingMethod ? $shippingMethod->name : null,
                    'payment_method'     => 'momo',
                    'discount_code'      => $orderDiscountCode?->code,
                    'free_shipping_code' => $freeShippingCode?->code,
                    'discount_amount'    => $discountAmount,
                    'total_amount'       => $total,
                    'shipping_cost'      => $shippingCost,
                    'payment_ref'        => $momoOrderId,
                    'payment_txn_id'     => $momoTransId,
                    'status'             => 'pending',
                    'payment_status'     => 'paid',
                ]);

                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id'                 => $item->product_id,
                        'product_name'               => $item->product->name ?? '',
                        'product_variant_value_id'   => $item->variant_id,
                        'product_variant_value_name' => $item->variant->name ?? null,
                        'product_sku'                => $item->product->sku ?? null,
                        'product_image'              => $item->product->image ?? null,
                        'quantity'                   => $item->quantity,
                        'price'                      => $item->price ?? 0,
                        'total'                      => ($item->price ?? 0) * ($item->quantity ?? 1),
                    ]);
                }

                // Cập nhật usage của coupon
                if ($orderDiscountCode && $orderDiscountCode->scope === 'global') $orderDiscountCode->increment('used_count');
                if ($freeShippingCode && $freeShippingCode->scope === 'global')   $freeShippingCode->increment('used_count');

                $singleUse = ['new_user_30d', 'first_order'];
                if ($orderDiscountCode && $orderDiscountCode->scope === 'conditional' && in_array($orderDiscountCode->condition_type, $singleUse)) {
                    \App\Models\admin\DiscountCodeUsage::firstOrCreate([
                        'discount_code_id' => $orderDiscountCode->id,
                        'user_id'          => $user->id,
                        'order_id'         => $order->id,
                        'used_at'          => now(),
                    ]);
                }
                if ($freeShippingCode && $freeShippingCode->scope === 'conditional' && in_array($freeShippingCode->condition_type, $singleUse)) {
                    \App\Models\admin\DiscountCodeUsage::firstOrCreate([
                        'discount_code_id' => $freeShippingCode->id,
                        'user_id'          => $user->id,
                        'order_id'         => $order->id,
                        'used_at'          => now(),
                    ]);
                }

                // Gửi mail (best-effort)
                $order->loadMissing('user');
                if ($order->user && $order->user->email) {
                    try { \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order)); }
                    catch (\Throwable $e) { \Log::warning('Mail order status failed', ['order_id' => $order->id, 'error' => $e->getMessage()]); }
                }
            });

            // Dọn giỏ & session
            \App\Models\Client\CartItem::where('user_id', $user->id)->whereIn('id', $selectedIds)->delete();
            session()->forget(['momo_selected_cart_item_ids', 'order_discount_code', 'free_shipping_code', 'shipping_method', 'address_id']);

            return redirect()->route('client.checkout.success');

        } catch (\Throwable $e) {
            // Hết hàng hoặc lỗi khác → REFUND ngay + trả thông báo
            try {
                app(\App\Services\Payments\RefundService::class)->refund((object)[
                    'payment_method' => 'momo',
                    'total_amount'   => $total,
                    'payment_txn_id' => $momoTransId,
                    'payment_ref'    => $momoOrderId,
                    'order_code'     => $momoOrderId,
                ], [
                    'amount'         => (int) round($total),
                    'reason'         => 'Out of stock on confirmation',
                    'transaction_id' => $momoTransId,
                ]);
            } catch (\Throwable $ex) {
                \Log::warning('Refund MoMo after OOS failed', ['err' => $ex->getMessage()]);
            }

            return redirect()->route('client.checkout')->with('error', 'Xin lỗi, sản phẩm vừa hết hàng. Giao dịch đã được hoàn tiền.');
        }
    }

    /* ======================================================================
     |  ZALOPAY REDIRECT SUCCESS
     |======================================================================*/
    if ($isZLPRedirect && $zlpSuccess) {
        \Log::info('ZaloPay redirect success', ['query' => $request->query()]);

        // Cart items
        $selectedIds = session('momo_selected_cart_item_ids', []);
        if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
        $selectedIds = array_filter(array_map('intval', $selectedIds));

        // Address
        $addressId = session('address_id');
        $address   = $addressId ? $user->addresses()->where('id', $addressId)->first() : null;
        if (!$address && session('address_snapshot')) {
            $s = session('address_snapshot');
            $address = $user->addresses()->create([
                'recipient_name' => $s['recipient_name'],
                'phone'          => $s['phone'],
                'address'        => $s['address'],
                'province'       => $s['province'],
                'district'       => $s['district'],
                'ward'           => $s['ward'],
                'is_default'     => true,
            ]);
            session()->forget('address_snapshot');
            session(['address_id' => $address->id]);
        }
        if (!$address) return redirect()->route('client.checkout')->with('error', 'Bạn cần nhập địa chỉ giao hàng!');

        $cartItems = \App\Models\Client\CartItem::with(['product','variant'])
            ->where('user_id', $user->id)->whereIn('id', $selectedIds)->get();
        if ($cartItems->isEmpty()) return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống hoặc sản phẩm không hợp lệ!');

        // Shipping & coupon
        $shippingMethodId = session('shipping_method', 1);
        $shippingMethod   = \App\Models\admin\ShippingMethod::find($shippingMethodId);
        $province         = $address->province ?? null;
        $shippingCostBase = $this->getShippingCostByProvince($province, $shippingMethodId);

        $orderDiscountCodeStr = session('order_discount_code');
        $freeShippingCodeStr  = session('free_shipping_code');
        $orderDiscountCode = null;
        $freeShippingCode  = null;

        if ($orderDiscountCodeStr) {
            $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
                ->where('type', '!=', 'free_shipping')->where('active', 1)->lockForUpdate()->first();
        }
        if ($freeShippingCodeStr) {
            $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
                ->where('type', 'free_shipping')->where('active', 1)->lockForUpdate()->first();
        }
        if ($orderDiscountCode && $orderDiscountCode->scope === 'global' && $orderDiscountCode->used_count >= $orderDiscountCode->usage_limit) {
            return redirect()->route('client.checkout')->with('error', 'Mã giảm giá đã hết lượt sử dụng!');
        }
        if ($freeShippingCode && $freeShippingCode->scope === 'global' && $freeShippingCode->used_count >= $freeShippingCode->usage_limit) {
            return redirect()->route('client.checkout')->with('error', 'Mã miễn phí vận chuyển đã hết lượt sử dụng!');
        }

        // Tính tiền
        $subtotal = $cartItems->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1));
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
        $shippingCost = $freeShippingCode ? 0 : $shippingCostBase;
        $total        = $subtotal + $shippingCost - $discountAmount;

        // Lấy appTransId từ session (đã set khi tạo ZLP)
        $appTransId = session('zlp_app_trans_id') ?: $appTransId;
        if (!$appTransId) {
            // an toàn: sinh từ pre_order_code nếu có
            $preCode = session('pre_order_code');
            if ($preCode) {
                $appTransId = substr(date('ymd') . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '', $preCode), 0, 40);
            }
        }

        // txn id từ IPN cache hoặc query API
        $zlpTxnId = $this->pullZlpTxnId($appTransId);

        try {
            // 1) KHÓA + kiểm tra + TRỪ kho
            $this->consumeStockOrFail($cartItems);

            // 2) Tạo order + items
            DB::transaction(function () use ($user, $address, $shippingMethod, $orderDiscountCode, $freeShippingCode, $discountAmount, $shippingCost, $total, $cartItems, $appTransId, $zlpTxnId) {

                $order = \App\Models\admin\Order::create([
                    'user_id'            => $user->id,
                    'recipient_name'     => $address->recipient_name,
                    'phone'              => $address->phone,
                    'full_address'       => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
                    'shipping_method'    => $shippingMethod?->name,
                    'order_code'         => session('pre_order_code') ?: ('QQ' . date('Ymd') . '-' . mt_rand(1000, 9999)),
                    'payment_method'     => 'zalopay',
                    'discount_code'      => $orderDiscountCode?->code,
                    'free_shipping_code' => $freeShippingCode?->code,
                    'discount_amount'    => $discountAmount,
                    'total_amount'       => $total,
                    'shipping_cost'      => $shippingCost,
                    'status'             => 'pending',
                    'payment_status'     => 'paid',
                    'payment_ref'        => $appTransId,
                    'zp_trans_id'        => $zlpTxnId,
                    'payment_txn_id'     => $zlpTxnId,
                ]);

                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id'                 => $item->product_id,
                        'product_name'               => $item->product->name ?? '',
                        'product_variant_value_id'   => $item->variant_id,
                        'product_variant_value_name' => $item->variant->name ?? null,
                        'product_sku'                => $item->product->sku ?? null,
                        'product_image'              => $item->product->image ?? null,
                        'quantity'                   => $item->quantity,
                        'price'                      => $item->price ?? 0,
                        'total'                      => ($item->price ?? 0) * ($item->quantity ?? 1),
                    ]);
                }

                // Cập nhật usage coupon
                if ($orderDiscountCode && $orderDiscountCode->scope === 'global') $orderDiscountCode->increment('used_count');
                if ($freeShippingCode && $freeShippingCode->scope === 'global')   $freeShippingCode->increment('used_count');

                $singleUse = ['new_user_30d', 'first_order'];
                if ($orderDiscountCode && $orderDiscountCode->scope === 'conditional' && in_array($orderDiscountCode->condition_type, $singleUse)) {
                    \App\Models\admin\DiscountCodeUsage::firstOrCreate([
                        'discount_code_id' => $orderDiscountCode->id,
                        'user_id'          => $user->id,
                        'order_id'         => $order->id,
                        'used_at'          => now(),
                    ]);
                }
                if ($freeShippingCode && $freeShippingCode->scope === 'conditional' && in_array($freeShippingCode->condition_type, $singleUse)) {
                    \App\Models\admin\DiscountCodeUsage::firstOrCreate([
                        'discount_code_id' => $freeShippingCode->id,
                        'user_id'          => $user->id,
                        'order_id'         => $order->id,
                        'used_at'          => now(),
                    ]);
                }

                // Gửi mail (best-effort)
                $order->loadMissing('user');
                if ($order->user && $order->user->email) {
                    try { \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order)); }
                    catch (\Throwable $e) { \Log::warning('Mail order status failed', ['order_id' => $order->id, 'error' => $e->getMessage()]); }
                }
            });

            // Dọn giỏ + session
            \App\Models\Client\CartItem::where('user_id', $user->id)->whereIn('id', $selectedIds)->delete();
            session()->forget(['momo_selected_cart_item_ids','order_discount_code','free_shipping_code','shipping_method','address_id','pre_order_code','zlp_app_trans_id']);

            return redirect()->route('client.checkout.success');

        } catch (\Throwable $e) {
            // Hết hàng → Refund ZLP ngay, rồi trả về checkout
            try {
                $txn = $zlpTxnId ?: $this->pullZlpTxnId($appTransId);
                app(\App\Services\Payments\RefundService::class)->refund((object)[
                    'payment_method' => 'zalopay',
                    'total_amount'   => $total,
                    'payment_txn_id' => $txn,
                    'payment_ref'    => $appTransId,
                    'order_code'     => $appTransId,
                    'zp_trans_id'    => $txn,
                ], [
                    'amount'         => (int) round($total),
                    'reason'         => 'Out of stock on confirmation',
                    'transaction_id' => $txn,
                ]);
            } catch (\Throwable $ex) {
                \Log::warning('Refund ZLP after OOS failed', ['err' => $ex->getMessage()]);
            }

            return redirect()->route('client.checkout')->with('error', 'Xin lỗi, sản phẩm vừa hết hàng. Giao dịch đã được hoàn tiền.');
        }
    }

    /* ======================================================================
     |  KHÔNG PHẢI CALLBACK: hiển thị trang checkout
     |======================================================================*/
    if (!$request->has('selected_cart_item_ids') && !$isMomoCallback && !$isZLPRedirect) {
        session()->forget(['order_discount_code', 'free_shipping_code']);
    }

    $orderDiscountCodeStr = session('order_discount_code');
    $freeShippingCodeStr  = session('free_shipping_code');
    $orderDiscountCode = null;
    $freeShippingCode  = null;
    if ($orderDiscountCodeStr) {
        $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
            ->where('type', '!=', 'free_shipping')->where('active', 1)->first();
    }
    if ($freeShippingCodeStr) {
        $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
            ->where('type', 'free_shipping')->where('active', 1)->first();
    }

    // Lấy address mặc định/đã chọn
    $addressId = session('address_id');
    $address = $addressId
        ? $user->addresses()->where('id', $addressId)->first()
        : $user->addresses()->where('is_default', 1)->first();

    // Lấy selected cart ids từ request/session
    $selectedRaw = $request->input('selected_cart_item_ids', '');
    if (is_array($selectedRaw)) {
        $selectedIds = array_map('intval', $selectedRaw);
    } else {
        $selectedIds = array_filter(array_map('intval', explode(',', $selectedRaw)));
    }
    if (empty($selectedIds)) {
        $selectedIds = array_filter(array_map('intval', session('momo_selected_cart_item_ids', [])));
    }

    $cartItems = \App\Models\Client\CartItem::with(['product','variant'])
        ->where('user_id', $user->id)
        ->when(count($selectedIds) > 0, fn($q) => $q->whereIn('id', $selectedIds))
        ->get();
    if ($cartItems->isEmpty()) return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');

    $shippingMethods  = \App\Models\admin\ShippingMethod::where('active', 1)->get();
    $shippingMethodId = session('shipping_method');
    if (!$shippingMethods->pluck('id')->contains($shippingMethodId)) {
        $shippingMethodId = optional($shippingMethods->first())->id;
        session(['shipping_method' => $shippingMethodId]);
    }
    $shippingMethod = $shippingMethods->firstWhere('id', $shippingMethodId);

    $subtotal = $cartItems->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1));

    // Gợi ý các code còn hiệu lực để hiển thị
    $now = now();
    $validDiscountCodes = \App\Models\admin\DiscountCode::where('active', 1)
        ->where(function ($q) use ($now) { $q->whereNull('start_date')->orWhere('start_date', '<=', $now); })
        ->where(function ($q) use ($now) { $q->whereNull('end_date')->orWhere('end_date', '>=', $now); })
        ->where(function ($q) {
            $q->where('scope', 'conditional')
              ->orWhere(function ($q2) {
                  $q2->where('scope', 'global')->whereNotNull('usage_limit')->whereRaw('used_count < usage_limit');
              });
        })
        ->get()
        ->filter(function ($code) use ($subtotal, $user) {
            $singleUseConditionTypes = ['new_user_30d', 'first_order'];
            if ($code->scope === 'conditional' && $code->condition_type === 'first_order') {
                $hasOrder = \App\Models\admin\Order::where('user_id', $user->id)->where('status', '!=', 'cancelled')->exists();
                if ($hasOrder) return false;
                if ($code->min_order_amount && $subtotal < $code->min_order_amount) return false;
                return true;
            }
            if ($code->scope === 'conditional' && in_array($code->condition_type, $singleUseConditionTypes)) {
                $alreadyUsed = \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $code->id)
                    ->where('user_id', $user->id)->exists();
                if ($alreadyUsed) return false;
                if ($code->condition_type === 'new_user_30d') {
                    $days = $user->created_at->diffInDays(now());
                    if ($days >= 30) return false;
                }
                if ($code->min_order_amount && $subtotal < $code->min_order_amount) return false;
                return true;
            }
            if ($code->scope === 'global') {
                $alreadyUsed = \App\Models\admin\Order::where('user_id', $user->id)
                    ->where(function ($q) use ($code) {
                        $q->where('discount_code', $code->code)->orWhere('free_shipping_code', $code->code);
                    })
                    ->where('status', '!=', 'cancelled')->exists();
                if ($alreadyUsed) return false;

                if ($code->usage_limit && $code->used_count >= $code->usage_limit) return false;
                if ($code->min_order_amount && $subtotal < $code->min_order_amount) return false;
                return true;
            }
            return !$code->min_order_amount || $subtotal >= $code->min_order_amount;
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

    $province     = $address->province ?? null;
    $shippingCost = $freeShippingCode ? 0 : $this->getShippingCostByProvince($province, $shippingMethodId);
    $total        = $subtotal + $shippingCost - $discountAmount;

    $appliedDiscountCodes = collect();
    if ($orderDiscountCode) $appliedDiscountCodes->push($orderDiscountCode);
    if ($freeShippingCode)  $appliedDiscountCodes->push($freeShippingCode);

    return view('frontend.checkout.checkout', [
        'addresses'              => $addresses,
        'address'                => $address,
        'cartItems'              => $cartItems,
        'shippingMethods'        => $shippingMethods,
        'shippingMethodId'       => $shippingMethodId,
        'shippingMethod'         => $shippingMethod,
        'subtotal'               => $subtotal,
        'shippingCost'           => $shippingCost,
        'discountAmount'         => $discountAmount,
        'total'                  => $total,
        'validDiscountCodes'     => $validDiscountCodes,
        'appliedDiscountCodes'   => $appliedDiscountCodes,
        'selected_cart_item_ids' => $selectedIds,
        'momoResult'             => null,
    ]);
}

/* ============================================================
 |  Helper: khóa & trừ kho nguyên tử (thất bại -> throw)
 * ============================================================*/
private function consumeStockOrFail($cartItems): void
{
    DB::transaction(function () use ($cartItems) {
        $variantIds = $cartItems->pluck('variant_id')->filter()->unique()->values();
        $productIds = $cartItems->whereNull('variant_id')->pluck('product_id')->unique()->values();

        if ($variantIds->isNotEmpty()) {
            DB::table('product_variants')->whereIn('id', $variantIds)->lockForUpdate()->get();
        }
        if ($productIds->isNotEmpty()) {
            DB::table('products')->whereIn('id', $productIds)->lockForUpdate()->get();
        }

        // Kiểm tra đủ kho
        foreach ($cartItems as $item) {
            $qty = (int) $item->quantity;
            if ($item->variant_id) {
                $row = DB::table('product_variants')->where('id', $item->variant_id)->first();
                $available = max(0, (int)$row->stock);
                if ($available < $qty) {
                    $pname = $item->product->name ?? ('SP#'.$item->product_id);
                    $vname = $item->variant->name ?? ('Var#'.$item->variant_id);
                    throw new \Exception("Sản phẩm \"{$pname} - {$vname}\" chỉ còn {$available}.");
                }
            } else {
                $row = DB::table('products')->where('id', $item->product_id)->first();
                $available = max(0, (int)$row->stock);
                if ($available < $qty) {
                    $pname = $item->product->name ?? ('SP#'.$item->product_id);
                    throw new \Exception("Sản phẩm \"{$pname}\" chỉ còn {$available}.");
                }
            }
        }

        // Trừ kho
        foreach ($cartItems as $item) {
            $qty = (int) $item->quantity;
            if ($item->variant_id) {
                DB::table('product_variants')
                    ->where('id', $item->variant_id)
                    ->update(['stock' => DB::raw("GREATEST(stock - {$qty}, 0)")]);
            } else {
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->update(['stock' => DB::raw("GREATEST(stock - {$qty}, 0)")]);
            }
        }
    });
}

/* ============================================================
 |  Helper: lấy ZLP transaction id từ cache hoặc query API
 * ============================================================*/
private function pullZlpTxnId(?string $appTransId): ?string
{
    if (!$appTransId) return null;

    if ($zpt = cache()->pull('zlp:ipn:' . $appTransId)) return $zpt;

    try {
        return app(\App\Services\Payments\RefundService::class)
            ->queryZlpTransIdByAppTransId($appTransId);
    } catch (\Throwable $e) {
        \Log::warning('queryZlpTransId failed', ['app_trans_id' => $appTransId, 'err' => $e->getMessage()]);
        return null;
    }
}


public function processOrder(Request $request)
{
    try {
        return \DB::transaction(function () use ($request) {
            $user = auth()->user();

            $validatedAddress = $request->validate([
                'address_id'     => 'nullable|exists:addresses,id',
                'recipient_name' => 'required|string|max:100',
                'phone'          => 'required|string|max:20',
                'province'       => 'required|string|max:100',
                'district'       => 'required|string|max:100',
                'ward'           => 'required|string|max:100',
                'address'        => 'required|string',
            ]);

            if ($request->filled('address_id')) {
                $address = $user->addresses()->findOrFail($request->input('address_id'));
                $address->update($validatedAddress);
            } else {
                $address = $user->addresses()->create($validatedAddress);
            }
            $address->update(['is_default' => true]);
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);

            $shipping_method = (int)$request->input('shipping_method', 1);
            $payment_method  = (string)$request->input('payment_method', 'cod');

            $selectedIds = $request->input('selected_cart_item_ids', []);
            if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
            $selectedIds = array_map('intval', $selectedIds);

            $cartItems = \App\Models\Client\CartItem::with(['product', 'variant'])
                ->where('user_id', $user->id)
                ->when(count($selectedIds) > 0, fn($q) => $q->whereIn('id', $selectedIds))
                ->get();
            if ($cartItems->isEmpty()) throw new \Exception('Giỏ hàng trống!');

            // stock check
            foreach ($cartItems as $item) {
                if ($item->variant_id) {
                    $variant = \App\Models\admin\ProductVariant::find($item->variant_id);
                    $stock   = $variant?->stock ?? 0;
                    if ($stock < $item->quantity) {
                        throw new \Exception('Sản phẩm "' . ($item->product->name ?? '') . ' - ' . ($variant->name ?? '') . '" hiện chỉ còn ' . $stock . ' sản phẩm.');
                    }
                } else {
                    $product = \App\Models\admin\Product::find($item->product_id);
                    $stock   = $product?->stock ?? 0;
                    if ($stock < $item->quantity) {
                        throw new \Exception('Sản phẩm "' . ($item->product->name ?? '') . '" hiện chỉ còn ' . $stock . ' sản phẩm.');
                    }
                }
            }

            $shippingMethod = \App\Models\admin\ShippingMethod::find($shipping_method);
            $province       = $address->province ?? $request->input('province');

            // lock coupons
            $orderDiscountCodeStr = session('order_discount_code');
            $freeShippingCodeStr  = session('free_shipping_code');
            $orderDiscountCode = null;
            $freeShippingCode  = null;

            if ($orderDiscountCodeStr) {
                $orderDiscountCode = \App\Models\admin\DiscountCode::where('code', $orderDiscountCodeStr)
                    ->where('type', '!=', 'free_shipping')->where('active', 1)->lockForUpdate()->first();
            }
            if ($freeShippingCodeStr) {
                $freeShippingCode = \App\Models\admin\DiscountCode::where('code', $freeShippingCodeStr)
                    ->where('type', 'free_shipping')->where('active', 1)->lockForUpdate()->first();
            }

            $subtotal = $cartItems->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1));

            if ($orderDiscountCode && $orderDiscountCode->min_order_amount && $subtotal < $orderDiscountCode->min_order_amount) {
                throw new \Exception('Đơn hàng chưa đủ giá trị tối thiểu để áp dụng mã giảm giá.');
            }
            if ($orderDiscountCode && $orderDiscountCode->scope === 'global' && $orderDiscountCode->used_count >= $orderDiscountCode->usage_limit) {
                throw new \Exception('Mã giảm giá đã hết lượt sử dụng!');
            }
            if ($freeShippingCode && $freeShippingCode->scope === 'global' && $freeShippingCode->used_count >= $freeShippingCode->usage_limit) {
                throw new \Exception('Mã miễn phí vận chuyển đã hết lượt sử dụng!');
            }

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
            $total        = $subtotal + $shippingCost - $discountAmount;

            $isGateway      = in_array($payment_method, ['momo', 'zalopay'], true);
            $status         = $isGateway ? 'confirmed' : 'pending';
            $payment_status = $isGateway ? 'paid'      : 'unpaid';

            $order = \App\Models\admin\Order::create([
                'user_id'            => $user->id,
                'recipient_name'     => $address->recipient_name,
                'phone'              => $address->phone,
                'full_address'       => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
                'shipping_method'    => $shippingMethod->name ?? null,
                'payment_method'     => $payment_method,
                'discount_code'      => $orderDiscountCode?->code,
                'free_shipping_code' => $freeShippingCode?->code,
                'discount_amount'    => $discountAmount,
                'shipping_cost'      => $shippingCost,
                'total_amount'       => $total,
                'status'             => $status,
                'payment_status'     => $payment_status,
            ]);

            // nếu lỡ có IPN trước khi tạo order
            if (!$order->zp_trans_id) {
                if ($zpt = cache()->pull('zlp:ipn:' . $order->order_code)) {
                    $order->zp_trans_id = $zpt;
                    $order->save();
                }
            }

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id'                 => $item->product_id,
                    'product_name'               => $item->product->name ?? '',
                    'product_variant_value_id'   => $item->variant_id,
                    'product_variant_value_name' => $item->variant->name ?? null,
                    'product_sku'                => $item->product->sku ?? null,
                    'product_image'              => $item->product->image ?? null,
                    'quantity'                   => $item->quantity,
                    'price'                      => $item->price ?? 0,
                    'total'                      => ($item->price ?? 0) * ($item->quantity ?? 1),
                ]);
                if ($item->variant_id) {
                    $variant = \App\Models\admin\ProductVariant::find($item->variant_id);
                    if ($variant) { $variant->stock = max(0, $variant->stock - $item->quantity); $variant->save(); }
                } else {
                    $product = \App\Models\admin\Product::find($item->product_id);
                    if ($product) { $product->stock = max(0, $product->stock - $item->quantity); $product->save(); }
                }
            }

            if ($orderDiscountCode && $orderDiscountCode->scope === 'global') $orderDiscountCode->increment('used_count');
            if ($freeShippingCode && $freeShippingCode->scope === 'global')   $freeShippingCode->increment('used_count');

            $singleUseConditionTypes = ['new_user_30d', 'first_order'];
            if ($orderDiscountCode && $orderDiscountCode->scope === 'conditional' && in_array($orderDiscountCode->condition_type, $singleUseConditionTypes)) {
                \App\Models\admin\DiscountCodeUsage::firstOrCreate([
                    'discount_code_id' => $orderDiscountCode->id,
                    'user_id'          => $user->id,
                    'order_id'         => $order->id,
                    'used_at'          => now(),
                ]);
            }
            if ($freeShippingCode && $freeShippingCode->scope === 'conditional' && in_array($freeShippingCode->condition_type, $singleUseConditionTypes)) {
                \App\Models\admin\DiscountCodeUsage::firstOrCreate([
                    'discount_code_id' => $freeShippingCode->id,
                    'user_id'          => $user->id,
                    'order_id'         => $order->id,
                    'used_at'          => now(),
                ]);
            }

            $order->loadMissing('user');
            if ($order->user && $order->user->email) {
                try { \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order)); }
                catch (\Throwable $e) { \Log::warning('Không gửi được mail trạng thái đơn hàng', ['order_id' => $order->id, 'error' => $e->getMessage()]); }
            }

            \App\Models\Client\CartItem::where('user_id', $user->id)
                ->when(count($selectedIds) > 0, fn($q) => $q->whereIn('id', $selectedIds))
                ->delete();

            session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method']);

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
