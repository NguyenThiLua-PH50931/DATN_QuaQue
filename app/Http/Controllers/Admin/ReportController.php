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

    // ---- LẤY NĂM từ request, dùng cho biểu đồ trend ----
    $year = $request->input('year', date('Y')); // <--- CHỈ DÙNG DÒNG NÀY!
    $date = $request->input('rating_date');
    $from = $request->input('rating_from_date');
    $to   = $request->input('rating_to_date');

    // Khởi tạo query cho filter ngày/tháng (cho card, table,...)
    $orderQuery = Order::query();
    $orderItemQuery = OrderItem::query();
    $reviewQuery = Review::query();
    $userQuery = User::query();

    // Apply filter ngày nếu có
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

    // Card thống kê chính
    $totalRevenue = (clone $orderQuery)->where('status', 'delivered')->sum('total_amount');
    $completedOrders = (clone $orderQuery)->where('status', 'delivered')->count();

    // ------ PHẦN ĐANG LÀM: Trend chart theo NĂM ------
    $monthlyStats = Order::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(total_amount) as revenue'),
        DB::raw('COUNT(id) as orders')
    )
    ->where('status', 'delivered')
    ->whereYear('created_at', $year)
    ->groupBy(DB::raw('MONTH(created_at)'))
    ->orderBy('month')
    ->get();

    // ...Các phần khác bạn giữ nguyên như cũ
    $topProductVariants = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('product_variants', 'order_items.product_variant_value_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->where('orders.status', 'delivered')
        ->select(
            'product_variants.id',
            'products.name as product_name',
            'product_variants.name as variant_name',
            'product_variants.image',
            'product_variants.price',
            'product_variants.stock',
            DB::raw('SUM(order_items.quantity) as sold_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
        )
        ->groupBy(
            'product_variants.id',
            'products.name',
            'product_variants.name',
            'product_variants.image',
            'product_variants.price',
            'product_variants.stock'
        )
        ->orderByDesc('sold_quantity')
        ->get();

    $regionSales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('product_variants', 'order_items.product_variant_value_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->join('regions', 'products.region_id', '=', 'regions.id')
        ->where('orders.status', 'delivered')
        ->select(
            'regions.id',
            'regions.name',
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
            DB::raw('SUM(order_items.quantity) as total_quantity_sold')
        )
        ->groupBy('regions.id', 'regions.name')
        ->orderByDesc('total_quantity_sold')
        ->get();

    $newUsers = User::whereMonth('created_at', date('m'))
        ->whereYear('created_at', $year)
        ->count();

    $totalRequests = SupportRequest::count();

    $ratedProducts = Review::join('products', 'reviews.product_id', '=', 'products.id')
        ->select(
            'products.id',
            'products.name as product_name',
            'products.image',
            DB::raw('AVG(reviews.rating) as average_rating'),
            DB::raw('COUNT(reviews.id) as review_count')
        )
        ->groupBy('products.id', 'products.name', 'products.image')
        ->orderByDesc('average_rating')
        ->get();

    $completed = (clone $orderQuery)->where('status', 'delivered')->count();
    $canceled  = (clone $orderQuery)->where('status', 'cancelled')->count();

    // Trả về view với đầy đủ biến (truyền luôn $year để filter năm trên view nếu muốn)
    return view('backend.reports.dashboard', compact(
        'year',
        'monthlyStats',
        'totalRevenue',
        'completedOrders',
        'topProductVariants',
        'regionSales',
        'newUsers',
        'totalRequests',
        'ratedProducts',
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