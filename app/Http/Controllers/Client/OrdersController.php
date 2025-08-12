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

    // 1) Tải đơn thuộc về user
    /** @var \App\Models\admin\Order $order */
    $order = \App\Models\admin\Order::with(['items', 'user'])
        ->where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail();

    // 2) Kiểm tra trạng thái cho phép hủy
    if (in_array($order->status, ['cancelled','delivered','failed_delivery'], true)) {
        return back()->with('error', 'Đơn hàng hiện tại không thể hủy.');
    }
    if ($order->status !== 'pending') {
        // Nếu bạn muốn cho phép hủy thêm ở 'confirmed' thì nới điều kiện trên
        return back()->with('error', 'Đơn hàng đã được xử lý, không thể hủy.');
    }

    // 3) Lý do hủy (bắt buộc)
    $request->validate([
        'cancel_reason' => 'required_without:other_reason|string|nullable|max:500',
        'other_reason'  => 'nullable|string|max:500',
    ]);
    $reason = trim((string)($request->input('cancel_reason') === 'Lý do khác'
        ? $request->input('other_reason')
        : $request->input('cancel_reason')));
    if ($reason === '') $reason = 'Khách hàng yêu cầu hủy';

    // 4) Có cần hoàn tiền không?
    $isOnlineGateway = in_array($order->payment_method, ['momo','zalopay'], true);
    $needRefund      = $isOnlineGateway && $order->payment_status === 'paid';

    // 5) Nếu cần refund, chuẩn bị transaction_id (zp_trans_id)
    $transactionId = $order->zp_trans_id ?: $order->payment_txn_id;
    if ($needRefund && !$transactionId && $order->payment_method === 'zalopay') {
        // thử lấy qua app_trans_id (đang lưu ở payment_ref)
        try {
            $appTransId   = (string) ($order->payment_ref ?? '');
            if ($appTransId !== '') {
                $found = app(\App\Services\Payments\RefundService::class)
                    ->queryZlpTransIdByAppTransId($appTransId);
                if ($found) {
                    $transactionId           = $found;
                    $order->zp_trans_id      = $found;
                    $order->payment_txn_id   = $found;
                    $order->save();
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('[ClientCancel] query zp_trans_id fail', ['order_id' => $order->id, 'err' => $e->getMessage()]);
        }
    }

    // 6) Gọi refund trước (để biết trạng thái), nếu cần
    $refundRes   = null;
    $refundCode  = 0; // 1=success, 3=processing, else fail
    if ($needRefund) {
        try {
            $refundRes = app(\App\Services\Payments\RefundService::class)->refund($order, [
                'amount'        => (int) round($order->total_amount),
                'reason'        => $reason,
                'initiator'     => 'client:' . $user->id,
                'transaction_id'=> $transactionId, // có thể null -> service tự fallback thêm lần nữa
            ]);
            $refundCode = (int) ($refundRes->code ?? 0);
            if (!$refundRes->success && $refundCode !== 3) {
                return back()->with('error', $refundRes->message ?: 'Hoàn tiền thất bại. Vui lòng thử lại.');
            }
        } catch (\Throwable $e) {
            \Log::error('[ClientCancel] refund exception', ['order_id' => $order->id, 'err' => $e->getMessage()]);
            return back()->with('error', 'Không thể hoàn tiền lúc này. Vui lòng thử lại sau.');
        }
    }

    // 7) Cập nhật tồn kho / coupon + set trạng thái đơn
    try {
        \DB::transaction(function () use ($order, $reason, $needRefund, $refundRes, $refundCode) {
            // Hoàn kho (variant-only như hệ thống của bạn)
            foreach ($order->items as $item) {
                if ($item->product_variant_value_id) {
                    $variant = \App\Models\admin\ProductVariant::find($item->product_variant_value_id);
                    if ($variant) $variant->increment('stock', (int) $item->quantity);
                }
            }

            // Trả lượt dùng coupon (nếu global)
            if ($order->discount_code) {
                $d = \App\Models\admin\DiscountCode::where('code', $order->discount_code)->first();
                if ($d && $d->scope === 'global' && $d->used_count > 0) $d->decrement('used_count');
                if ($d && $d->scope === 'conditional') {
                    \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $d->id)
                        ->where('user_id', $order->user_id)->delete();
                }
            }
            if ($order->free_shipping_code) {
                $f = \App\Models\admin\DiscountCode::where('code', $order->free_shipping_code)->first();
                if ($f && $f->scope === 'global' && $f->used_count > 0) $f->decrement('used_count');
                if ($f && $f->scope === 'conditional') {
                    \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $f->id)
                        ->where('user_id', $order->user_id)->delete();
                }
            }

            // Trạng thái & thanh toán
            $order->status        = 'cancelled';
            $order->cancel_reason = $reason;

            if ($needRefund) {
                $order->refund_amount  = (int) round($order->total_amount);
                $order->refund_ref     = $refundRes->reference ?? $order->refund_ref;
                $order->refund_message = $refundRes->message  ?? $order->refund_message;

                if ($refundCode === 1) {
                    $order->refund_status  = 'success';
                    $order->refunded_at    = now();
                    $order->payment_status = 'refunded'; // nếu không muốn đổi thì comment dòng này
                } elseif ($refundCode === 3) {
                    $order->refund_status  = 'pending';
                    // giữ payment_status = paid cho đến khi query_refund xác nhận
                } else {
                    $order->refund_status  = 'failed';
                    // giữ payment_status = paid
                }
            } else {
                // COD / unpaid
                $order->payment_status = 'unpaid';
                $order->refund_status  = 'none';
                $order->refund_amount  = null;
                $order->refund_ref     = null;
                $order->refund_message = null;
                $order->refunded_at    = null;
            }

            $order->save();

            // Log thay đổi (nếu có bảng log)
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
        \Log::error('[ClientCancel] DB transaction failed', ['order_id' => $order->id, 'err' => $e->getMessage()]);
        return back()->with('error', 'Có lỗi khi cập nhật đơn hàng, vui lòng thử lại.');
    }

    // 8) Gửi mail best-effort
    try {
        if ($order->user && $order->user->email) {
            \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
        }
    } catch (\Throwable $e) {
        \Log::warning('[ClientCancel] send mail failed', ['order_id' => $order->id, 'err' => $e->getMessage()]);
    }

    // 9) Trả về
    $msg = 'Đã hủy đơn hàng.';
    if ($needRefund) {
        $msg .= ($refundCode === 1) ? ' Hoàn tiền thành công.' :
                (($refundCode === 3) ? ' Yêu cầu hoàn tiền đang xử lý.' : ' Hoàn tiền thất bại.');
    }

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

}
