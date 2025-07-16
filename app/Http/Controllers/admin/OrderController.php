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

        return view('backend.orders.index', compact('orders'));
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

        if ($newStatusRank < $currentStatusRank && !in_array($newStatus, ['cancelled', 'failed_delivery'])) {
            return response()->json([
                'message' => 'Không thể quay lại trạng thái trước đó.',
            ], 422);
        }

        // Ghi log trạng thái
        OrderStatusLog::create([
            'order_id'    => $order->id,
            'from_status' => $oldStatus,
            'to_status'   => $newStatus,
            'changed_at'  => now(),
        ]);

        // Nếu là trạng thái cần hoàn lại kho
        if (!in_array($oldStatus, ['cancelled', 'failed_delivery']) && in_array($newStatus, ['cancelled', 'failed_delivery'])) {
            $order->loadMissing('items');

            if ($order->items->isEmpty()) {
                \Log::warning('Không có item nào trong đơn hàng khi hủy:', ['order_id' => $order->id]);
            }

            foreach ($order->items as $item) {
                \Log::info('Đang xử lý item:', [
                    'order_item_id' => $item->id,
                    'variant_id' => $item->product_variant_value_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ]);

                if ($item->product_variant_value_id) {
                    $variant = \App\Models\admin\ProductVariant::find($item->product_variant_value_id);
                    if ($variant) {
                        $variant->stock += (int) $item->quantity;
                        $variant->save();
                        \Log::info('>> Đã cộng lại stock cho biến thể', ['variant_id' => $variant->id, 'stock' => $variant->stock]);
                    } else {
                        \Log::error('Không tìm thấy biến thể:', ['variant_id' => $item->product_variant_value_id]);
                    }
                } else {
                    $product = \App\Models\admin\Product::find($item->product_id);
                    if ($product) {
                        $product->stock += (int) $item->quantity;
                        $product->save();
                        \Log::info('>> Đã cộng lại stock cho sản phẩm thường', ['product_id' => $product->id, 'stock' => $product->stock]);
                    } else {
                        \Log::error('Không tìm thấy sản phẩm thường:', ['product_id' => $item->product_id]);
                    }
                }
            }
        }

        $order->status = $newStatus;

        if ($order->payment_method === 'cod') {
            $order->payment_status = ($newStatus === 'delivered') ? 'paid' : 'unpaid';
        }

        $order->save();

        $order->loadMissing('user');
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        }

        return response()->json(['message' => 'Cập nhật trạng thái đơn hàng thành công.']);
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
}
