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

class OrderController extends Controller
{
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
    $request->validate([
        'status' => 'required|in:pending,confirmed,processing,shipped,in_transit,delivered,cancelled,failed_delivery',
    ]);

    $statusOrder = [
        'pending' => 1,
        'confirmed' => 2,
        'processing' => 3,
        'shipped' => 4,
        'in_transit' => 5,
        'delivered' => 6,
        'cancelled' => 7,
        'failed_delivery' => 8,
    ];

    $oldStatus = $order->status;
    $newStatus = $request->status;

    $currentStatusRank = $statusOrder[$oldStatus] ?? 0;
    $newStatusRank = $statusOrder[$newStatus];

    // Không cho chuyển lùi trạng thái (trừ khi hủy/giao thất bại)
    if ($newStatusRank < $currentStatusRank && !in_array($newStatus, ['cancelled', 'failed_delivery'])) {
        $msg = 'Không thể quay lại trạng thái trước đó.';
        return $request->ajax()
            ? response()->json(['success' => false, 'message' => $msg], 422)
            : redirect()->back()->with('error', $msg);
    }

    // Ghi log trạng thái
    OrderStatusLog::create([
        'order_id'    => $order->id,
        'from_status' => $oldStatus,
        'to_status'   => $newStatus,
        'changed_at'  => now(),
    ]);

    // Nếu là trạng thái cần hoàn lại kho và hoàn lại lượt dùng mã giảm giá
    if (!in_array($oldStatus, ['cancelled', 'failed_delivery']) && in_array($newStatus, ['cancelled', 'failed_delivery'])) {
        $order->loadMissing('items');
        foreach ($order->items as $item) {
            if ($item->product_variant_value_id) {
                $variant = \App\Models\admin\ProductVariant::find($item->product_variant_value_id);
                if ($variant) {
                    $variant->stock += (int) $item->quantity;
                    $variant->save();
                }
            }
            // Nếu KHÔNG cộng lại kho cho product gốc thì không cần else!
        }

        // =============================
        //   HOÀN LẠI USED_COUNT COUPON
        // =============================
        if ($order->discount_code) {
            $coupon = \App\Models\admin\DiscountCode::where('code', $order->discount_code)->first();
            if ($coupon && $coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }
        }
        if ($order->free_shipping_code) {
            $coupon = \App\Models\admin\DiscountCode::where('code', $order->free_shipping_code)->first();
            if ($coupon && $coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }
        }
    }

    // Cập nhật trạng thái mới cho đơn hàng
    $order->status = $newStatus;

    // Cập nhật trạng thái thanh toán theo phương thức
    if ($order->payment_method === 'cod') {
        $order->payment_status = ($newStatus === 'delivered') ? 'paid' : 'unpaid';
    } elseif ($order->payment_method === 'momo' || $order->payment_method === 'bank') {
        // Với momo, bank thì giữ nguyên trạng thái thanh toán đã xử lý ở nơi khác
    }

    // Lưu thay đổi
    $order->save();

    // Thông báo email nếu cần
    $order->loadMissing('user');
    if ($order->user && $order->user->email) {
        try {
            \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
        } catch (\Throwable $e) {
            \Log::warning('Không gửi được mail trạng thái đơn hàng', [
                'order_id' => $order->id,
                'error'    => $e->getMessage()
            ]);
        }
    }

    // Trả về đầy đủ dữ liệu cho frontend JS cập nhật UI
    if ($request->ajax()) {
        return response()->json([
            'success'        => true,
            'message'        => 'Cập nhật trạng thái thành công!',
            'new_status'     => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'order_id'       => $order->id,
            'is_hidden'      => $order->is_hidden ?? false,
        ]);
    }

    return redirect()->route('admin.orders.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
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

}
