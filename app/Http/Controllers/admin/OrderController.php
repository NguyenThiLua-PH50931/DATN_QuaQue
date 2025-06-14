<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Order;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng

public function index(Request $request)
{
    $query = Order::query();

    // Lọc trạng thái đơn hàng
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Lọc trạng thái thanh toán
    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    // Lọc phương thức thanh toán
    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    // Lọc ngày đặt từ
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    // Lọc ngày đặt đến
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    // Lọc từ khóa (mã đơn hàng hoặc người đặt)
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function ($q) use ($keyword) {
            $q->where('order_code', 'like', "%{$keyword}%")
              ->orWhereHas('user', function ($q2) use ($keyword) {
                  $q2->where('name', 'like', "%{$keyword}%");
              });
        });
    }

    // Lấy danh sách đơn hàng có phân trang
    $orders = $query->with('user', 'items')->orderBy('created_at', 'desc')->paginate(15);

    return view('backend.orders.index', compact('orders'));
}



    // Hiển thị chi tiết đơn hàng
    public function show(Order $order)
    {
        // Load các thông tin liên quan đến đơn hàng, như các sản phẩm và người dùng
        $order->load(['items', 'user', 'address']);

        // Trả về view 'backend.orders.show' với dữ liệu đơn hàng
        return view('backend.orders.show', compact('order'));
    }

    // Hiển thị trang tracking (nếu cần)
    public function tracking(Order $order)
    {
        // Load các thông tin liên quan (ví dụ items, user, address nếu cần)
        $order->load(['items', 'user', 'address']);

        // Mô hình trạng thái các bước tracking chi tiết hơn
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

            $currentStatusRank = $statusOrder[$order->status];
            $newStatusRank = $statusOrder[$request->status];

            if ($newStatusRank < $currentStatusRank && !in_array($request->status, ['cancelled', 'failed_delivery'])) {
                return response()->json([
                    'message' => 'Lỗi !.',
                ], 422);
            }

            $order->status = $request->status;
            $order->save();

            // ⚠ Load user để có email (nếu chưa eager load trước đó)
            $order->load('user');

            // ✅ Gửi mail
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            }

            return response()->json([
                'message' => 'Cập nhật trạng thái đơn hàng thành công.',
                'status' => $order->status,
            ]);
        }


    public function destroy(Order $order)
    {
        // Kiểm tra trạng thái hợp lệ trước khi xóa
        if (!in_array($order->status, ['delivered', 'cancelled', 'failed_delivery'])) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể xóa đơn hàng đã giao, đã hủy hoặc giao thất bại.');
        }

        // Xóa đơn hàng
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công.');
    }
}

