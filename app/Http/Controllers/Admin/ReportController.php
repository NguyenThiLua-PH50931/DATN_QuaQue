<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\Admin\Review;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        if ($request->has('reset')) {
            return redirect()->route('admin.dashboard');
        }

        $year = date('Y');
        $date = $request->input('rating_date');
        $from = $request->input('rating_from_date');
        $to   = $request->input('rating_to_date');

        // Khởi tạo query
        $orderQuery = Order::query();
        $orderItemQuery = OrderItem::query();
        $reviewQuery = Review::query();
        $userQuery = User::query();

        // Apply filters nếu có
        if ($date) {
            $orderQuery->whereDate('orders.created_at', $date);
            $orderItemQuery->whereDate('order_items.created_at', $date);
            $reviewQuery->whereDate('reviews.created_at', $date);
            $userQuery->whereDate('users.created_at', $date);
        } else {
            if ($from) {
                $orderQuery->whereDate('orders.created_at', '>=', $from);
                $orderItemQuery->whereDate('order_items.created_at', '>=', $from);
                $reviewQuery->whereDate('reviews.created_at', '>=', $from);
                $userQuery->whereDate('users.created_at', '>=', $from);
            }
            if ($to) {
                $orderQuery->whereDate('orders.created_at', '<=', $to);
                $orderItemQuery->whereDate('order_items.created_at', '<=', $to);
                $reviewQuery->whereDate('reviews.created_at', '<=', $to);
                $userQuery->whereDate('users.created_at', '<=', $to);
            }
        }

        // Tổng doanh thu
        $totalRevenue = (clone $orderQuery)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Số đơn hoàn thành
        $completedOrders = (clone $orderQuery)
            ->where('status', 'completed')
            ->count();

        // Sản phẩm bán chạy nhất
        $topProduct = (clone $orderItemQuery)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->first();

        // Vùng bán chạy nhất
        $topRegion = (clone $orderQuery)
            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
            ->where('orders.status', 'completed')
            ->select(
                'addresses.address',
                DB::raw('SUM(orders.total_amount) as total_revenue')
            )
            ->groupBy('addresses.address')
            ->orderByDesc('total_revenue')
            ->first();

        // Người dùng mới trong tháng hiện tại
        $newUsers = User::whereMonth('created_at', date('m'))
            ->whereYear('created_at', $year)
            ->count();

        // Tổng số yêu cầu hỗ trợ
        $totalRequests = SupportRequest::count();

        // Sản phẩm được đánh giá cao nhất
        $topRatedProduct = (clone $reviewQuery)
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('AVG(reviews.rating) as average_rating')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('average_rating')
            ->first();

        // Thống kê trạng thái đơn hàng
        $completed = (clone $orderQuery)->where('status', 'completed')->count();
        $canceled  = (clone $orderQuery)->where('status', 'cancelled')->count();

        // Xử lý trường hợp không có dữ liệu
        // $topProduct = $topProduct ?? (object)['name' => 'N/A', 'total_revenue' => 0];
        // $topRegion = $topRegion ?? (object)['address' => 'N/A', 'total_revenue' => 0];
        // $topRatedProduct = $topRatedProduct ?? (object)['name' => 'N/A', 'average_rating' => 0];

        return view('backend.reports.dashboard', compact(
            'totalRevenue',
            'completedOrders',
            'topProduct',
            'topRegion',
            'newUsers',
            'totalRequests',
            'topRatedProduct',
            'completed',
            'canceled',
            'date',
            'from',
            'to'
        ));
    }


    public function revenueByMonthYear(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $revenue = Order::withTrashed()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->where('status', 'delivered')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        return view('admin.reports.revenue', compact('revenue', 'year'));
    }

    public function completedOrders()
    {
        $completedOrders = Order::withTrashed()
            ->where('status', 'delivered')
            ->count();

        return view('admin.reports.completed_orders', compact('completedOrders'));
    }

    public function topProductRevenue()
    {
        $topProduct = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'delivered')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->first();

        return view('admin.reports.top_product', compact('topProduct'));
    }

    public function topRegionRevenue()
    {
        $topRegion = Order::withTrashed()
            ->join('regions', 'orders.region_id', '=', 'regions.id')
            ->where('orders.status', 'delivered')
            ->select(
                'regions.name',
                DB::raw('SUM(orders.total) as total_revenue')
            )
            ->groupBy('regions.id', 'regions.name')
            ->orderByDesc('total_revenue')
            ->first();

        return view('admin.reports.top_region', compact('topRegion'));
    }

    public function newUsers(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $newUsers = User::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_users')
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        return view('admin.reports.new_users', compact('newUsers', 'year'));
    }

    public function supportRequests()
    {
        $totalRequests = SupportRequest::count();
        return view('admin.reports.support_requests', compact('totalRequests'));
    }

    public function topRatedProduct()
    {
        $topRatedProduct = Review::join('products', 'reviews.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('AVG(reviews.rating) as average_rating')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('average_rating')
            ->first();

        return view('admin.reports.top_rated_product', compact('topRatedProduct'));
    }

    public function orderStatus()
    {
        $completed = Order::withTrashed()
            ->where('status', 'delivered')
            ->count();

        $canceled = Order::withTrashed()
            ->where('status', 'cancelled')
            ->count();

        return view('admin.reports.order_status', compact('completed', 'canceled'));
    }
}
