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

    // Huỷ đơn hàng (đã fix chỉ cộng lại kho cho variant!)
public function cancel(Request $request, $id)
{
    $user = Auth::user();

    $order = ClientOrder::with(['items'])->where('user_id', $user->id)->where('id', $id)->firstOrFail();

    if (in_array($order->status, ['cancelled', 'failed_delivery'])) {
        return redirect()->route('client.orders.index')
            ->with('info', 'Đơn hàng đã được hủy trước đó.');
    }

    // Lý do hủy
    $cancelReason = $request->input('cancel_reason');
    if ($cancelReason == 'Lý do khác') {
        $cancelReason = $request->input('other_reason');
    }

    // 1. Cộng lại kho cho variant (chỉ cộng variant, không cộng product gốc)
    foreach ($order->items as $item) {
        if ($item->product_variant_value_id) {
            $variant = ProductVariant::find($item->product_variant_value_id);
            if ($variant) {
                $variant->stock += intval($item->quantity);
                $variant->save();
            }
        }
    }

    // 2. Cộng lại lượt dùng mã giảm giá nếu có
// 2. Hoàn lại lượt dùng mã giảm giá nếu có
if ($order->discount_code) {
    $discount = \App\Models\admin\DiscountCode::where('code', $order->discount_code)->first();
    if ($discount) {
        if ($discount->scope === 'global' && $discount->used_count > 0) {
            $discount->decrement('used_count');
        }
        if ($discount->scope === 'conditional') {
            \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $discount->id)
                ->where('user_id', $order->user_id)
                ->delete();
        }
    }
}
if ($order->free_shipping_code) {
    $freeShipping = \App\Models\admin\DiscountCode::where('code', $order->free_shipping_code)->first();
    if ($freeShipping) {
        if ($freeShipping->scope === 'global' && $freeShipping->used_count > 0) {
            $freeShipping->decrement('used_count');
        }
        if ($freeShipping->scope === 'conditional') {
            \App\Models\admin\DiscountCodeUsage::where('discount_code_id', $freeShipping->id)
                ->where('user_id', $order->user_id)
                ->delete();
        }
    }
}


    // 3. Cập nhật trạng thái đơn hàng
    $order->status = 'cancelled';
    $order->payment_status = 'unpaid';
    $order->cancel_reason = $cancelReason;
    $order->save();

    // 4. Log trạng thái (nếu có table status logs)
    if (class_exists(\App\Models\admin\OrderStatusLog::class)) {
        \App\Models\admin\OrderStatusLog::create([
            'order_id' => $order->id,
            'from_status' => $order->status,
            'to_status' => 'cancelled',
            'changed_at' => now(),
        ]);
    }

    // 5. Gửi email xác nhận huỷ đơn
    if ($order->user && $order->user->email) {
        Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
    }

    return redirect()->route('client.orders.index')
        ->with('success', 'Huỷ đơn hàng thành công!');
}


}
