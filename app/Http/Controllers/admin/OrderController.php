<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Order;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use App\Models\admin\OrderStatusLog;
class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng

public function index(Request $request)
{
    $query = Order::query();

    // Luôn lọc theo is_hidden (nếu không truyền thì mặc định là 0)
    $isHidden = $request->input('is_hidden', '0');
    $query->where('is_hidden', $isHidden);

    // Các bộ lọc khác giữ nguyên
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    // Lọc phương thức thanh toán
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

    return view('backend.orders.index', compact('orders'));
}





    // Hiển thị chi tiết đơn hàng
    public function show(Order $order)
{
    $order->load([
        'items.productVariant.attributeValues.attribute',
        'items.productVariant.product.firstImage', // THÊM DÒNG NÀY
        'user',
        'address'
    ]);

    return view('backend.orders.show', compact('order'));
}



    // Hiển thị trang tracking (nếu cần)
 public function tracking(Order $order)
{
    $order->load(['items', 'user', 'address', 'statusLogs']); // thêm 'statusLogs' ở đây

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


    // Hàm cập nhật trạng thái đơn hàng
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

    $currentStatusRank = $statusOrder[$order->status] ?? 0;
    $newStatusRank = $statusOrder[$request->status];

    // Không cho phép lùi trạng thái (trừ khi hủy hoặc giao thất bại)
    if ($newStatusRank < $currentStatusRank && !in_array($request->status, ['cancelled', 'failed_delivery'])) {
        return response()->json([
            'message' => 'Không thể quay lại trạng thái trước đó.',
        ], 422);
    }

    // Ghi log trước khi thay đổi
    OrderStatusLog::create([
        'order_id'    => $order->id,
        'from_status' => $order->status,
        'to_status'   => $request->status,
        'changed_at'  => now(),
    ]);

    // Cập nhật trạng thái đơn hàng
    $order->status = $request->status;

    // Nếu là COD thì xử lý trạng thái thanh toán
    if ($order->payment_method === 'cod') {
        $order->payment_status = ($request->status === 'delivered') ? 'paid' : 'unpaid';
    }

    $order->save();

    $order->loadMissing('user');

    if ($order->user && $order->user->email) {
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
    }

    return response()->json([
        'message' => 'Cập nhật trạng thái đơn hàng thành công.',
        'status' => $order->status,
        'payment_status' => $order->payment_status,
    ]);
}
    public function destroy(Order $order)
{
    if (!in_array($order->status, ['delivered', 'cancelled', 'failed_delivery'])) {
        return redirect()->back()->with('error', 'Chỉ được ẩn đơn hàng đã giao, đã hủy hoặc giao thất bại.');
    }

    $order->delete(); // Soft delete

    return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được ẩn.');
}
// OrderController.php
public function hide(Order $order)
{
    if (!in_array($order->status, ['delivered', 'cancelled', 'failed_delivery'])) {
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Chỉ được ẩn đơn hàng đã giao, hủy, hoặc giao thất bại.',
                'is_hidden' => $order->is_hidden
            ], 422);
        }
        return back()->with('error', 'Chỉ được ẩn đơn hàng đã giao, hủy, hoặc giao thất bại.');
    }

    $order->is_hidden = true;
    $order->save();

    if (request()->expectsJson()) {
        return response()->json([
            'message' => 'Đơn hàng đã được ẩn.',
            'is_hidden' => true
        ]);
    }

    return back()->with('success', 'Đơn hàng đã được ẩn.');
}
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


}

