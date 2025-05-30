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
        $orders = Order::with('user')->latest()->get();
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
        return view('backend.orders.tracking', compact('order'));
    }

    // Hàm cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled',
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
    if (!in_array($order->status, ['completed', 'cancelled'])) {
        return redirect()->back()->with('error', 'Bạn chỉ có thể xóa đơn hàng đã giao hoặc đã hủy.');
    }

    // Xóa đơn hàng
    $order->delete();

    return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công.');
}


}
