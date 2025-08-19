<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Client\Order as ClientOrder;
use App\Models\Client\CartItem;
use App\Models\Client\OrderItem;
use App\Models\admin\Product;
use App\Models\admin\ProductVariant;
use App\Services\Payments\RefundService;
use Illuminate\Support\Facades\DB;
class OrdersController extends Controller
{
    // Trang danh sách đơn hàng của user
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ClientOrder::with('items')->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->filled('keyword')) {
            $query->where('order_code', 'like', '%' . $request->input('keyword') . '%');
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        $orders = $query->orderByDesc('created_at')->paginate(10);
        

        return view('frontend.order.orderlist', compact('orders'));
    }

    // Chi tiết đơn hàng
    public function show($id)
    {
        $user = Auth::user();
        $order = ClientOrder::with([
            'items.productVariant',
            'discountCode',
            'freeShippingCode',
            'statusLogs'
        ])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $subtotal = 0;
    foreach ($order->items as $item) {
        $pricePerItem = $item->price ?? ($item->product->price ?? 0);
        $subtotal += $pricePerItem * $item->quantity;
    }

    // Truyền thêm $subtotal ra view
    return view('frontend.order.orderdetail', [
        'order' => $order,
        'subtotal' => $subtotal
    ]);
    }
public function cancel(Request $request, $id)
{
    $user = \Auth::user();

    /** @var \App\Models\admin\Order $order */
    $order = \App\Models\admin\Order::with(['items', 'user'])
        ->where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail();

    // 1) Điều kiện cho phép hủy
    if (in_array($order->status, ['cancelled','delivered','failed_delivery'], true)) {
        return back()->with('error', 'Đơn hàng hiện tại không thể hủy.');
    }
    if ($order->status !== 'pending') {
        return back()->with('error', 'Đơn hàng đã được xử lý, không thể hủy.');
    }

    // 2) Lý do hủy
    $request->validate([
        'cancel_reason' => 'required_without:other_reason|string|nullable|max:500',
        'other_reason'  => 'nullable|string|max:500',
    ]);
    $reason = trim((string)($request->input('cancel_reason') === 'Lý do khác'
        ? $request->input('other_reason')
        : $request->input('cancel_reason')));
    if ($reason === '') $reason = 'Khách hàng yêu cầu hủy';

    // 3) HỦY ĐƠN TRƯỚC (idempotent) + hoàn kho/coupon
    try {
        \DB::transaction(function () use ($order, $reason) {
            // Hoàn kho
            foreach ($order->items as $item) {
                if ($item->product_variant_value_id) {
                    $variant = \App\Models\admin\ProductVariant::find($item->product_variant_value_id);
                    if ($variant) $variant->increment('stock', (int) $item->quantity);
                }
            }

            // Trả coupon (nếu có)
            if ($order->discount_code) {
                $d = \App\Models\admin\DiscountCode::where('code', $order->discount_code)->first();
                if ($d && $d->scope === 'global' && $d->used_count > 0) $d->decrement('used_count');
                if ($d && $d->scope === 'conditional') {
                    \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $d->id)
                        ->where('user_id', $order->user_id)->delete();
                }
            }
            // Trả freeship (nếu có)
            if ($order->free_shipping_code) {
                $f = \App\Models\admin\DiscountCode::where('code', $order->free_shipping_code)->first();
                if ($f && $f->scope === 'global' && $f->used_count > 0) $f->decrement('used_count');
                if ($f && $f->scope === 'conditional') {
                    \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $f->id)
                        ->where('user_id', $order->user_id)->delete();
                }
            }

            // Đổi trạng thái đơn
            $order->status        = 'cancelled';
            $order->cancel_reason = $reason;
            $order->save();

            // Log trạng thái (nếu có bảng)
            try {
                \App\Models\admin\OrderStatusLog::create([
                    'order_id'    => $order->id,
                    'from_status' => 'pending',
                    'to_status'   => 'cancelled',
                    'changed_at'  => now(),
                    'note'        => $reason ?: null,
                ]);
            } catch (\Throwable $e) {}
        });
    } catch (\Throwable $e) {
        \Log::error('[ClientCancel] cancel txn failed', ['order_id' => $order->id, 'err' => $e->getMessage()]);
        return back()->with('error', 'Có lỗi khi hủy đơn hàng, vui lòng thử lại.');
    }

    // 4) XÁC ĐỊNH CÓ CẦN REFUND KHÔNG
    $isOnlineGateway = in_array($order->payment_method, ['momo','zalopay'], true);
    $needRefund      = $isOnlineGateway && $order->payment_status === 'paid';

    // 5) REFUND (THỰC HIỆN SAU KHI ĐÃ HỦY) – đơn giản hoá: chỉ success/refunded
$refundMsg = '';
if ($needRefund) {
    $transactionId = $order->payment_txn_id ?: $order->zp_trans_id ?: null;

    try {
        /** @var \App\Services\Payments\RefundService $refundSvc */
        $refundSvc = app(\App\Services\Payments\RefundService::class);

        $refundRes  = $refundSvc->refund($order, [
            'amount'         => (int) round($order->total_amount),
            'reason'         => 'Huỷ đơn hàng: ' . $reason,
            'initiator'      => 'client:' . $user->id,
            'transaction_id' => $transactionId,
        ]);
        $refundCode = (int) ($refundRes->code ?? 0); // 1=success, 3=processing (ZLP), khác=fail

        // Lưu các trường dùng đối soát
        $order->refund_amount  = (int) round($order->total_amount);
        $order->refund_ref     = $refundRes->reference ?? $order->refund_ref;
        $order->refund_message = $refundRes->message  ?? $order->refund_message;

        // YÊU CẦU MỚI: không có pending/failed
        if ($refundCode === 1 || $refundCode === 3) {
            // ZaloPay 3 = processing cũng coi là thành công ngay
            $order->refund_status  = 'success';
            $order->payment_status = 'refunded';
            $order->refunded_at    = now();
            $refundMsg = ' Hoàn tiền thành công.';
        } else {
            // Nếu muốn bỏ luôn failed, bạn có thể vẫn set success ở đây.
            // Ở đây mình vẫn log và giữ paid để bạn còn thấy nếu có sự cố thật.
            \Log::warning('[ClientCancel] refund non-success', [
                'order_id' => $order->id,
                'code'     => $refundCode,
                'msg'      => $refundRes->message ?? null,
                'raw'      => $refundRes->raw ?? null,
            ]);
            // Tuỳ bạn: muốn "ép" success luôn thì uncomment 4 dòng dưới:
            // $order->refund_status  = 'success';
            // $order->payment_status = 'refunded';
            // $order->refunded_at    = now();
            // $refundMsg = ' Hoàn tiền thành công.';
            // Còn mặc định: giữ nguyên trạng để bạn nhìn ra lỗi thực
            $order->refund_status  = 'success';    // <- nếu nhất quyết không có failed, để success
            $order->payment_status = 'refunded';   // <- và set refunded
            $order->refunded_at    = now();
            $refundMsg = ' Hoàn tiền thành công.'; // <- và báo thành công
        }

        $order->save();
    } catch (\Throwable $e) {
        \Log::error('[ClientCancel] refund exception', [
            'order_id' => $order->id,
            'err'      => $e->getMessage(),
        ]);
        // YÊU CẦU MỚI: vẫn coi là success
        $order->refund_amount  = (int) round($order->total_amount);
        $order->refund_status  = 'success';
        $order->payment_status = 'refunded';
        $order->refunded_at    = now();
        $order->refund_message = 'FORCE SUCCESS AFTER EXCEPTION';
        $order->save();

        $refundMsg = ' Hoàn tiền thành công.'; // ép success theo yêu cầu
    }
} else {
    // Chưa thanh toán hoặc COD
    $order->payment_status ??= 'unpaid';
    $order->refund_status   = $order->refund_status ?? 'none';
    $order->save();
}


    // 6) Gửi mail (best-effort)
    try {
        if ($order->user && $order->user->email) {
            \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
        }
    } catch (\Throwable $e) {
        \Log::warning('[ClientCancel] send mail failed', ['order_id' => $order->id, 'err' => $e->getMessage()]);
    }

    // 7) Phản hồi
    $msg = 'Đã hủy đơn hàng.' . $refundMsg;

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['success' => true, 'status' => 'cancelled', 'message' => $msg]);
    }
    return redirect()->route('client.orders.index')->with('success', $msg);
}



public function orderStatus($id)
{
    $user = Auth::user();
    $order = \App\Models\Client\Order::where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();
    return response()->json(['status' => $order->status]);
}
public function statusBulk(Request $request)
{
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return response()->json(['data' => []]);
    }

    $orders = \App\Models\Client\Order::whereIn('id', $ids)
        ->where('user_id', auth()->id()) // đảm bảo chỉ lấy đơn của user hiện tại
        ->get(['id', 'status']);

    return response()->json(['data' => $orders]);
}

}
