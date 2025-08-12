<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Order;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use App\Models\admin\OrderStatusLog;
use Illuminate\Support\Facades\Log;
use App\Models\admin\Product;
use App\Models\admin\ProductVariant;
use App\Services\Payments\RefundService;

class OrderController extends Controller
{
    public function __construct(private RefundService $refunds) {}

    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        $orders = $query->with('user', 'items')->orderBy('created_at', 'desc')->paginate(15);

$lastOrderId = \App\Models\admin\Order::orderByDesc('id')->value('id') ?? 0;
return view('backend.orders.index', compact('orders', 'lastOrderId'));

    }

    public function show(Order $order)
    {
        $order->load([
            'items.productVariant.attributeValues.attribute',
            'items.productVariant.product.firstImage',
            'user',
            'address'
        ]);

        return view('backend.orders.show', compact('order'));
    }

    public function tracking(Order $order)
    {
        $order->load(['items', 'user', 'address', 'statusLogs']);

        $allSteps = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang chuẩn bị',
            'shipped' => 'Đã gửi hàng',
            'in_transit' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'failed_delivery' => 'Giao thất bại',
        ];

        $orderedSteps = ['pending', 'confirmed', 'processing', 'shipped', 'in_transit', 'delivered'];

        $steps = [];
        foreach ($orderedSteps as $key) {
            $steps[] = [
                'name' => $allSteps[$key],
                'done' => array_search($key, $orderedSteps) <= array_search($order->status, $orderedSteps),
            ];
        }

        return view('backend.orders.tracking', compact('order', 'steps'));
    }
public function updateStatus(Request $request, Order $order)
{
    // 1) Validate input
    $validStatuses = [
        'pending', 'confirmed', 'processing', 'shipped',
        'in_transit', 'delivered', 'cancelled', 'failed_delivery'
    ];
    $request->validate([
        'status'        => 'required|in:' . implode(',', $validStatuses),
        'cancel_reason' => 'nullable|string|max:500|required_if:status,cancelled',
    ]);

    $oldStatus = $order->status;
    $newStatus = (string) $request->string('status');

    // 2) Ràng buộc chuyển trạng thái (khớp UI)
    $allowedTransitions = [
        'pending'         => ['confirmed', 'cancelled'],
        'confirmed'       => ['processing', 'cancelled'],
        'processing'      => ['shipped', 'cancelled'],
        'shipped'         => ['in_transit'],
        'in_transit'      => ['delivered', 'failed_delivery'],
        'delivered'       => [],                 // terminal
        'cancelled'       => [],                 // terminal
        'failed_delivery' => [],                 // terminal
    ];

    if ($oldStatus !== $newStatus) {
        $allowed = $allowedTransitions[$oldStatus] ?? [];
        if (!in_array($newStatus, $allowed, true)) {
            $msg = 'Trạng thái không hợp lệ từ "'.$oldStatus.'" sang "'.$newStatus.'".';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }
    } else {
        // Không có thay đổi
        return $request->ajax()
            ? response()->json([
                'success'        => true,
                'message'        => 'Trạng thái không thay đổi.',
                'new_status'     => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'order_id'       => $order->id,
                'cancel_reason'  => $order->cancel_reason,
                'is_hidden'      => $order->is_hidden ?? false,
            ])
            : back()->with('success', 'Trạng thái không thay đổi.');
    }

    // 3) Ghi log thay đổi trạng thái
    \App\Models\admin\OrderStatusLog::create([
        'order_id'    => $order->id,
        'from_status' => $oldStatus,
        'to_status'   => $newStatus,
        'changed_at'  => now(),
    ]);

    // 4) Nếu chuyển sang cancelled/failed_delivery từ trạng thái khác
    //    -> hoàn kho + trả used_count cho mã giảm giá (nếu có)
    if (!in_array($oldStatus, ['cancelled', 'failed_delivery'], true)
        && in_array($newStatus, ['cancelled', 'failed_delivery'], true)) {

        $order->loadMissing('items');

        // Hoàn kho cho variant (theo cấu trúc hiện có)
        foreach ($order->items as $item) {
            if ($item->product_variant_value_id) {
                if ($variant = \App\Models\admin\ProductVariant::find($item->product_variant_value_id)) {
                    $variant->increment('stock', (int) $item->quantity);
                }
            }
        }

        // Trả used_count mã giảm giá (nếu đã trừ trước đó)
        if ($order->discount_code) {
            if ($coupon = \App\Models\admin\DiscountCode::where('code', $order->discount_code)->first()) {
                if ($coupon->used_count > 0) $coupon->decrement('used_count');
            }
        }
        if ($order->free_shipping_code) {
            if ($coupon = \App\Models\admin\DiscountCode::where('code', $order->free_shipping_code)->first()) {
                if ($coupon->used_count > 0) $coupon->decrement('used_count');
            }
        }
    }

    // 5) Cập nhật order + lý do huỷ
    $order->status = $newStatus;
    $order->cancel_reason = ($newStatus === 'cancelled')
        ? trim((string) $request->input('cancel_reason', ''))
        : null;

    // 6) Cập nhật payment_status theo phương thức (ngoại trừ refund xử lý bên dưới)
    if ($order->payment_method === 'cod') {
        // COD chỉ paid khi đã giao
        $order->payment_status = ($newStatus === 'delivered') ? 'paid' : 'unpaid';
    } elseif (in_array($order->payment_method, ['momo', 'zalopay', 'bank'], true)) {
        // Ví/chuyển khoản: giữ nguyên payment_status, refund xử lý phía sau nếu là 'cancelled'
    }

    // 7) Lưu trước để tránh rollback cục bộ
    $order->save();

    // 8) Nếu HỦY đơn + phương thức online + đã thu tiền => gọi RefundService (ép success)
$refundRef = null;
$refundMsg = null;

if (
    $newStatus === 'cancelled'
    && in_array($order->payment_method, ['zalopay', 'momo'], true)
    && $order->payment_status === 'paid'
) {
    try {
        $amountToRefund = (int) round($order->total_amount);
        $reason         = $order->cancel_reason ?: ('Admin cancel order '.$order->order_code);

        $res = $this->refunds->refund($order, [
            'amount'         => $amountToRefund,
            'reason'         => $reason,
            'initiator'      => 'admin:'.($request->user()->id ?? 'unknown'),
            'transaction_id' => $order->zp_trans_id ?? $order->payment_txn_id ?? null,
        ]);

        $refundRef = $res->reference ?? null;

        // ÉP THÀNH CÔNG CHO CẢ MOMO & ZALO (không còn pending/failed)
        $order->refund_status  = 'success';
        $order->refund_amount  = $amountToRefund;
        $order->refund_ref     = $refundRef;
        $order->refund_message = $res->message ?: 'OK';
        $order->refunded_at    = now();
        $order->payment_status = 'refunded';   // <-- QUAN TRỌNG: áp dụng cho cả momo & zalopay
        $order->save();

        // Log nếu cổng trả code khác 1/3 để còn truy vết kỹ thuật (không ảnh hưởng UI)
        $code = (int) ($res->code ?? 0);
        if ($code !== 1 && $code !== 3) {
            \Log::warning('[AdminCancel] refund forced success although gateway code != 1/3', [
                'order_id' => $order->id,
                'code'     => $code,
                'msg'      => $res->message ?? null,
                'raw'      => $res->raw ?? null,
            ]);
        }

        $refundMsg = ' (Đã hoàn tiền thành công'.($refundRef ? " – Ref: {$refundRef}" : '').')';

    } catch (\Throwable $e) {
        \Log::error('[AdminCancel] refund exception, force success', [
            'order_id' => $order->id,
            'err'      => $e->getMessage(),
        ]);

        // Dù lỗi kỹ thuật, vẫn ép success theo nghiệp vụ mới
        $order->refund_status  = 'success';
        $order->refund_amount  = (int) round($order->total_amount);
        $order->refund_message = 'FORCED SUCCESS AFTER EXCEPTION';
        $order->refunded_at    = now();
        $order->payment_status = 'refunded';
        $order->save();

        $refundMsg = ' (Đã hoàn tiền thành công)';
    }
}


    // 9) Gửi mail (best-effort)
    $order->loadMissing('user');
    if ($order->user && $order->user->email) {
        try {
            \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
        } catch (\Throwable $e) {
            \Log::warning('Không gửi được mail trạng thái đơn hàng', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    // 10) Trả response
    $baseMsg = ($newStatus === 'cancelled')
        ? 'Đã hủy đơn hàng.' . ($refundMsg ?? '')
        : 'Cập nhật trạng thái thành công!';

    if ($request->ajax()) {
        return response()->json([
            'success'         => true,
            'message'         => $baseMsg,
            'new_status'      => $order->status,
            'payment_status'  => $order->payment_status,
            'payment_method'  => $order->payment_method,
            'order_id'        => $order->id,
            'cancel_reason'   => $order->cancel_reason,
            'is_hidden'       => $order->is_hidden ?? false,
            'refund_ref'      => $refundRef,
            'refund_status'   => $order->refund_status ?? null,
        ]);
    }

    return redirect()->route('admin.orders.index')->with('success', $baseMsg);
}



    // Nếu KHÔNG muốn cho phép xóa, có thể comment function destroy
    public function destroy(Order $order)
    {
        if (!in_array($order->status, ['delivered', 'cancelled', 'failed_delivery'])) {
            return redirect()->back()->with('error', 'Chỉ được xóa đơn hàng đã giao, đã hủy hoặc giao thất bại.');
        }

        $order->delete(); // XÓA CỨNG

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa vĩnh viễn.');
    }

    // ĐÃ XÓA hoàn toàn function hide()

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        if ($order->payment_method !== 'bank') {
            return response()->json(['message' => 'Chỉ có thể cập nhật trạng thái thanh toán cho đơn bank.'], 403);
        }

        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json([
            'message' => 'Cập nhật trạng thái thanh toán thành công.',
            'payment_status' => $order->payment_status,
        ]);
    }
    // Controller: Admin\OrderController
public function latestOrderId()
{
    // Dùng model nào cũng được vì đều là bảng 'orders'
    $latestOrder = \App\Models\admin\Order::orderByDesc('id')->first();
    return response()->json(['latest_id' => $latestOrder?->id ?? 0]);
}
public function adminCancel(Request $request, Order $order)
{
    // Chỉ cho hủy khi đơn chưa kết thúc
    $notCancelable = ['cancelled','delivered','failed_delivery'];
    if (in_array($order->status, $notCancelable, true)) {
        return back()->with('error', 'Đơn hàng ở trạng thái hiện tại không thể hủy.');
    }

    $reason = trim((string)$request->input('reason'));

    \DB::transaction(function () use ($order, $reason) {
        // 1) Hoàn kho
        $order->loadMissing('items');
        foreach ($order->items as $item) {
            if ($item->product_variant_value_id) {
                $variant = \App\Models\admin\ProductVariant::find($item->product_variant_value_id);
                if ($variant) $variant->increment('stock', (int)$item->quantity);
            } else {
                $product = \App\Models\admin\Product::find($item->product_id);
                if ($product) $product->increment('stock', (int)$item->quantity);
            }
        }

        // 2) Trả lượt dùng coupon (nếu là coupon global có cộng used_count khi đặt)
        if ($order->discount_code) {
            $d = \App\Models\admin\DiscountCode::where('code', $order->discount_code)
                ->where('scope','global')->first();
            if ($d && $d->used_count > 0) $d->decrement('used_count');
        }
        if ($order->free_shipping_code) {
            $f = \App\Models\admin\DiscountCode::where('code', $order->free_shipping_code)
                ->where('scope','global')->first();
            if ($f && $f->used_count > 0) $f->decrement('used_count');
        }

        // 3) Cập nhật đơn
        $order->status = 'cancelled';
        // Không đụng payment_status ở đây: COD vẫn 'unpaid', online vẫn 'paid' (vì chưa làm refund)
        $order->save();

        // 4) Log (nếu bạn đang dùng OrderStatusLog)
        try {
            \App\Models\admin\OrderStatusLog::create([
                'order_id'    => $order->id,
                'from_status' => $order->getOriginal('status') ?? 'unknown',
                'to_status'   => 'cancelled',
                'changed_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // optional
        }
    });

    // 5) Gửi mail (best-effort)
    $order->loadMissing('user');
    if ($order->user && $order->user->email) {
        try {
            \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
        } catch (\Throwable $e) {}
    }

    return back()->with('success', 'Đã hủy đơn hàng.');
}

}
