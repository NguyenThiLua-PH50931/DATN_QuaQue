<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\admin\OrderItem;
use App\Models\admin\Review;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard()
    {
        $year = date('Y');
        $totalRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', $year)
            ->sum('total_amount');

        $completedOrders = Order::where('status', 'completed')->count();

        $topProduct = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('order_items.status', 'shipped') // Chỉ tính sản phẩm đã giao
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->first();

        $topRegion = Order::join('addresses', 'orders.address_id', '=', 'addresses.id') // Sử dụng address_id thay region_id
            ->where('orders.status', 'completed')
            ->select(
                'addresses.address', 
                DB::raw('SUM(orders.total_amount) as total_revenue')
            )
            ->groupBy('addresses.address')
            ->orderByDesc('total_revenue')
            ->first();

        $newUsers = User::whereMonth('created_at', date('m'))
            ->whereYear('created_at', $year)
            ->count();

        $totalRequests = SupportRequest::count();

        $topRatedProduct = Review::join('products', 'reviews.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('AVG(reviews.rating) as average_rating')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('average_rating')
            ->first();

        $completed = Order::where('status', 'completed')->count();
        $canceled = Order::where('status', 'cancelled')->count();

        return view('backend.reports.dashboard', compact(
            'totalRevenue',
            'completedOrders',
            'topProduct',
            'topRegion',
            'newUsers',
            'totalRequests',
            'topRatedProduct',
            'completed',
            'canceled'
        ));
    }

    public function revenueByMonthYear(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $revenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        return view('admin.reports.revenue', compact('revenue', 'year'));
    }

    public function completedOrders()
    {
        $completedOrders = Order::where('status', 'completed')->count();
        return view('admin.reports.completed_orders', compact('completedOrders'));
    }

    public function topProductRevenue()
    {
        $topProduct = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
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
        $topRegion = Order::join('regions', 'orders.region_id', '=', 'regions.id')
            ->where('orders.status', 'completed')
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
        $completed = Order::where('status', 'completed')->count();
        $canceled = Order::where('status', 'canceled')->count();
        return view('admin.reports.order_status', compact('completed', 'canceled'));
    }
}
