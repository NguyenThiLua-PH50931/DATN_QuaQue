<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Client\Order as ClientOrder;
use App\Models\Client\CartItem;
use App\Models\Client\OrderItem;
use App\Models\Client\PendingPayment;
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

        return view('frontend.order.orderdetail', compact('order'));
    }

    // Huỷ đơn hàng
    public function cancel(Request $request, $id)
{
    $user = Auth::user();

    $order = ClientOrder::with(['items'])->where('user_id', $user->id)->where('id', $id)->firstOrFail();

    if (in_array($order->status, ['cancelled', 'failed_delivery'])) {
        return redirect()->route('client.orders.index')
            ->with('info', 'Đơn hàng đã được hủy trước đó.');
    }

    // Lấy lý do từ request
    $cancelReason = $request->input('cancel_reason');
    if ($cancelReason == 'Lý do khác') {
        $cancelReason = $request->input('other_reason');
    }

    // Cộng lại kho cho variant hoặc product gốc
    foreach ($order->items as $item) {
        if ($item->product_variant_value_id) {
            $variant = ProductVariant::find($item->product_variant_value_id);
            if ($variant) {
                $variant->stock += intval($item->quantity);
                $variant->save();
            }
        } else {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock += intval($item->quantity);
                $product->save();
            }
        }
    }

    // Cập nhật trạng thái & lý do huỷ
    $order->status = 'cancelled';
    $order->payment_status = 'unpaid';
    $order->cancel_reason = $cancelReason; // <- Lưu lý do
    $order->save();

    // Gửi email xác nhận huỷ đơn
    if ($order->user && $order->user->email) {
        Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
    }

    return redirect()->route('client.orders.index')
        ->with('success', 'Huỷ đơn hàng thành công!');
}


    // Tạo đơn hàng từ pending payment đã thanh toán MoMo
   public function placeFromPending(Request $request)
{
    $user = auth()->user();
    $pending = PendingPayment::where('id', $request->pending_payment_id)
        ->where('user_id', $user->id)
        ->where('status', 'paid')
        ->first();

    if (!$pending) {
        return back()->with('error', 'Không tìm thấy thanh toán đang chờ xử lý!');
    }

    // Lấy snapshot luôn!
    $snapshot = $pending->cart_items_snapshot;
    if (empty($snapshot) || !is_array($snapshot)) {
        return back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng hoặc dữ liệu đơn hàng!');
    }

    // Lấy địa chỉ (ưu tiên địa chỉ lúc pending, nếu lưu; nếu không thì lấy mặc định của user)
    $address = $user->addresses()->where('is_default', true)->first();
    if (!$address) {
        return back()->with('error', 'Bạn cần thêm địa chỉ giao hàng trước!');
    }

    // Lấy các trường giảm giá, freeship, ship method từ pending
    $discountCodeId      = $pending->discount_code_id ?? null;
    $freeShippingCodeId  = $pending->free_shipping_code_id ?? null;
    $discountAmount      = $pending->discount_amount ?? 0;
    $shippingMethodId    = $pending->shipping_method_id ?? session('shipping_method_id', 1);
    $shippingCost        = $pending->shipping_cost ?? 0;

    // Tạo đơn hàng từ snapshot (dữ liệu freeze)
    $order = \App\Models\Client\Order::create([
        // 'order_code'           => $pending->order_id,
        'user_id'              => $user->id,
        'recipient_name'       => $address->recipient_name,
        'phone'                => $address->phone,
        'full_address'         => $address->address . ', ' . ($address->ward ?? '') . ', ' . $address->district . ', ' . $address->province,
        'shipping_method_id'   => $shippingMethodId,
        'shipping_cost'        => $shippingCost,
        'discount_code_id'     => $discountCodeId,
        'free_shipping_code_id'=> $freeShippingCodeId,
        'discount_amount'      => $discountAmount,
        'total_amount'         => $pending->amount,
        'status'               => 'confirmed',
        'payment_method'       => 'momo',
        'payment_status'       => 'paid',
    ]);

    // Thêm item từ snapshot
    foreach ($snapshot as $item) {
        $product = \App\Models\admin\Product::find($item['product_id'] ?? null);
        $variant = !empty($item['variant_id']) ? \App\Models\admin\ProductVariant::find($item['variant_id']) : null;

        \App\Models\Client\OrderItem::create([
            'order_id'                   => $order->id,
            'product_id'                 => $item['product_id'] ?? null,
            'product_name'               => $item['product_name'] ?? ($product->name ?? ''),
            'product_variant_value_id'   => $item['variant_id'] ?? null,
            'product_variant_value_name' => $item['variant_name'] ?? ($variant->name ?? null),
            'product_sku'                => $item['sku'] ?? ($variant->sku ?? $product->sku ?? null),
            'product_image'              => $item['image'] ?? ($product->image ?? null),
            'quantity'                   => $item['quantity'] ?? 1,
            'price'                      => $item['price'] ?? ($product->price ?? 0),
            'total'                      => (($item['price'] ?? $product->price ?? 0) * ($item['quantity'] ?? 1)),
        ]);

        // Trừ kho thực tế
        if (!empty($item['variant_id']) && $variant) {
            $variant->stock = max(0, $variant->stock - ($item['quantity'] ?? 1));
            $variant->save();
        } elseif ($product) {
            $product->stock = max(0, $product->stock - ($item['quantity'] ?? 1));
            $product->save();
        }
    }

    // Xóa các cart item này nếu vẫn còn trong giỏ
    if (!empty($pending->cart_item_ids)) {
        \App\Models\Client\CartItem::where('user_id', $user->id)
            ->whereIn('id', $pending->cart_item_ids)
            ->delete();
    }

    // Cập nhật trạng thái pending
    $pending->status = 'processed';
    $pending->save();

    return redirect()->route('client.checkout.success')->with('success', 'Đơn hàng đã được tạo thành công!');
}

}
