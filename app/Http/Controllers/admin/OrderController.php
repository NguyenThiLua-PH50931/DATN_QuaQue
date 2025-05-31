<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Order;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index()
{
    $orders = Order::with('user')->latest()->paginate(10);
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
        // Validate dữ liệu đầu vào
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,in_transit,delivered,cancelled,failed_delivery',
        ]);

        // Cập nhật trạng thái
        $order->status = $request->status;
        $order->save();

        // Trả về trang trước đó với thông báo thành công
        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
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
