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
    public function dashboard()
    {
        $year = date('Y');

        $totalRevenue = Order::withTrashed()
            ->where('status', 'delivered')
            ->whereYear('created_at', $year)
            ->sum('total_amount');

        $completedOrders = Order::withTrashed()
            ->where('status', 'delivered')
            ->count();

        $topProduct = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
    ->where('orders.status', 'delivered')
    ->join('products', 'order_items.product_id', '=', 'products.id')
    ->select(
        'products.name',
        'products.created_at',   // Thêm dòng này!
        'products.image',        // Nếu muốn lấy luôn image
        DB::raw('SUM(order_items.total) as total_revenue')
    )
    ->groupBy('products.id', 'products.name', 'products.created_at', 'products.image')
    ->orderByDesc('total_revenue')
    ->first();



        $topRegion = Order::withTrashed()
            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
            ->where('orders.status', 'delivered')
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

        $completed = Order::withTrashed()
            ->where('status', 'delivered')
            ->count();

        $canceled = Order::withTrashed()
            ->where('status', 'cancelled')
            ->count();

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
