<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Client\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    // Trang danh sách đơn hàng của user
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with('items')->where('user_id', $user->id);

        // Lọc trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Lọc theo ngày đặt
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Lọc theo mã đơn hàng (keyword)
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%' . $request->input('keyword') . '%');
            });
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }
        $orders = $query->orderByDesc('created_at')->paginate(10);

        return view('frontend.order.orderlist', compact('orders'));
    }
    public function show($id)
    {
        $user = Auth::user();
        $order = Order::with([
            'items.productVariant',
            'discountCode',
            'freeShippingCode',
            'address',
            'statusLogs'
        ])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('frontend.order.orderdetail', compact('order'));
    }

    public function cancel($id)
    {
        $user = Auth::user();
        $order = Order::with([
            'items.productVariant',
            'discountCode',
            'address',
            'statusLogs' // <- Thêm dòng này
        ])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();


        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('client.orders.index')
            ->with('success', 'Huỷ đơn hàng thành công!');
    }
}
