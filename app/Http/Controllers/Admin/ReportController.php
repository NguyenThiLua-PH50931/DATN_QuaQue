<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\Admin\Review;
use App\Models\SupportRequest;
use App\Models\Admin\SupportTicket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ReportController extends Controller
{
    // Dashboard tổng quan báo cáo
public function dashboard(Request $request)
{
    // 1. FILTER CHO DASHBOARD TỔNG
    $fromDate = $request->input('from_date');
    $toDate   = $request->input('to_date');

    // Nếu chọn "Hôm nay"
    if ($request->has('today')) {
        $fromDate = $toDate = now()->format('Y-m-d');
    }

    if ($request->has('reset')) {
        return redirect()->route('admin.dashboard');
    }

    // 2. FILTER YEAR DÙNG CHO CHART
    $year = $request->input('year', now()->year);

    // 3. BLOCK TỔNG
    $userQuery   = User::query();
    $orderQuery  = Order::query()->where('status', 'delivered');
    $ticketQuery = SupportTicket::query();

    if ($fromDate) {
        $userQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        $orderQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        $ticketQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
    }
    if ($toDate) {
        $userQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        $orderQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        $ticketQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
    } else {
        // Nếu không có “đến ngày”, thì luôn chỉ lấy đến hôm nay
        $userQuery->where('created_at', '<=', now());
        $orderQuery->where('created_at', '<=', now());
        $ticketQuery->where('created_at', '<=', now());
    }

    $totalCustomers   = $userQuery->count();
    $totalRevenue     = $orderQuery->sum('total_amount');
    $completedOrders  = $orderQuery->count();
    $totalRequests    = $ticketQuery->count();

    // ==== Chart: Doanh thu, đơn từng tháng trong năm chọn ====
    $now = now();
    $monthlyStats = Order::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(total_amount) as revenue'),
        DB::raw('COUNT(id) as orders')
    )
    ->where('status', 'delivered')
    ->whereYear('created_at', $year)
    ->where('created_at', '<=', $now)
    ->groupBy(DB::raw('MONTH(created_at)'))
    ->orderBy('month')->get();

    // ==== Người dùng mới của tháng hiện tại trong năm chọn ====
    $newUsers = User::whereMonth('created_at', now()->month)
        ->whereYear('created_at', $year)->count();

    // 4. ==== DONUT HOÀN THÀNH/HUỶ THEO TUẦN/THÁNG/NĂM ====
    $donutStats = [
        'week' => [
            'completed' => Order::where('status', 'delivered')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'canceled'  => Order::where('status', 'cancelled')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ],
        'month' => [
            'completed' => Order::where('status', 'delivered')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'canceled'  => Order::where('status', 'cancelled')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ],
        'year' => [
            'completed' => Order::where('status', 'delivered')->whereYear('created_at', now()->year)->count(),
            'canceled'  => Order::where('status', 'cancelled')->whereYear('created_at', now()->year)->count(),
        ],
    ];

    // 5. ==== CÁC BLOCK PHỤ (Chỉ lọc theo tuần/tháng/năm, KHÔNG dùng fromDate/toDate) ====
    $type          = $request->input('type', 'week');
    $ratedType     = $request->input('rated_type', 'week');
    $cancelledType = $request->input('cancelled_type', 'week');
    $regionType    = $request->input('region_type', 'week');

    // ==== Top bán chạy ====
    $orderItemsQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('product_variants', 'order_items.product_variant_value_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->where('orders.status', 'delivered');

    if ($type === 'week') {
        $orderItemsQuery->whereBetween('order_items.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($type === 'month') {
        $orderItemsQuery->whereMonth('order_items.created_at', now()->month)->whereYear('order_items.created_at', now()->year);
    } elseif ($type === 'year') {
        $orderItemsQuery->whereYear('order_items.created_at', now()->year);
    }

    $bestSellingProducts = $orderItemsQuery
        ->select(
            'product_variants.id',
            'products.name as product_name',
            'product_variants.name as variant_name',
            'product_variants.image',
            'product_variants.price',
            'product_variants.stock',
            DB::raw('SUM(order_items.quantity) as sold_quantity'),
            DB::raw('MAX(order_items.created_at) as created_at')
        )
        ->groupBy(
            'product_variants.id',
            'products.name',
            'product_variants.name',
            'product_variants.image',
            'product_variants.price',
            'product_variants.stock'
        )
        ->orderByDesc('sold_quantity')->limit(10)->get()->toArray();

    // ==== Top đánh giá cao ====
    $reviewQuery = Review::join('products', 'reviews.product_id', '=', 'products.id')
        ->select(
            'products.id',
            'products.name as product_name',
            'products.image',
            DB::raw('AVG(reviews.rating) as average_rating'),
            DB::raw('COUNT(reviews.id) as review_count'),
            DB::raw('MAX(reviews.created_at) as created_at')
        );
    if ($ratedType === 'week') {
        $reviewQuery->whereBetween('reviews.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($ratedType === 'month') {
        $reviewQuery->whereMonth('reviews.created_at', now()->month)->whereYear('reviews.created_at', now()->year);
    } elseif ($ratedType === 'year') {
        $reviewQuery->whereYear('reviews.created_at', now()->year);
    }
    $topRatedProducts = $reviewQuery
        ->groupBy('products.id', 'products.name', 'products.image')
        ->orderByDesc('average_rating')
        ->orderByDesc('review_count')
        ->limit(10)->get()->toArray();

    // ==== Top bị huỷ nhiều nhất (theo tuần/tháng/năm) ====
    $cancelFrom = $cancelTo = null;
    if ($cancelledType === 'week') {
        $cancelFrom = now()->startOfWeek(); $cancelTo = now()->endOfWeek();
    } elseif ($cancelledType === 'month') {
        $cancelFrom = now()->startOfMonth(); $cancelTo = now()->endOfMonth();
    } elseif ($cancelledType === 'year') {
        $cancelFrom = now()->startOfYear(); $cancelTo = now()->endOfYear();
    }

    $cancelledItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->leftJoin('product_variants', 'order_items.product_variant_value_id', '=', 'product_variants.id')
        ->where('orders.status', 'cancelled')
        ->when($cancelFrom, fn($q) => $q->whereDate('orders.created_at', '>=', $cancelFrom))
        ->when($cancelTo, fn($q) => $q->whereDate('orders.created_at', '<=', $cancelTo))
        ->select(
            'products.id as product_id',
            'products.name as product_name',
            'products.image as product_image',
            'product_variants.id as variant_id',
            'product_variants.name as variant_name',
            DB::raw('SUM(order_items.quantity) as cancelled_qty'),
            DB::raw('COUNT(DISTINCT orders.id) as cancelled_orders'),
            DB::raw('MAX(orders.created_at) as last_cancelled'),
            DB::raw('GROUP_CONCAT(orders.cancel_reason) as cancel_reasons')
        )
        ->groupBy(
            'products.id',
            'products.name',
            'products.image',
            'product_variants.id',
            'product_variants.name'
        )
        ->get();

    $deliveredItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'delivered')
        ->when($cancelFrom, fn($q) => $q->whereDate('orders.created_at', '>=', $cancelFrom))
        ->when($cancelTo, fn($q) => $q->whereDate('orders.created_at', '<=', $cancelTo))
        ->select(
            'order_items.product_id',
            'order_items.product_variant_value_id',
            DB::raw('COUNT(DISTINCT orders.id) as delivered_orders')
        )
        ->groupBy('order_items.product_id', 'order_items.product_variant_value_id')
        ->get()
        ->keyBy(fn($item) => $item->product_id . '-' . ($item->product_variant_value_id ?? 0));

    // Gom biến thể vào cha
    $cancelProductsArr = [];
    foreach ($cancelledItems as $item) {
        $productId = $item->product_id;
        $variantKey = $item->product_id . '-' . ($item->variant_id ?? 0);
        $deliveredOrders = isset($deliveredItems[$variantKey]) ? $deliveredItems[$variantKey]->delivered_orders : 0;
        $cancelledOrders = $item->cancelled_orders;
        $totalOrders = $deliveredOrders + $cancelledOrders;
        $cancelPercent = $totalOrders > 0 ? round($cancelledOrders * 100 / $totalOrders, 2) : 0;

        $reasonsArr = array_filter(explode(',', $item->cancel_reasons));
        $topReason = '';
        if (!empty($reasonsArr)) {
            $counts = array_count_values($reasonsArr);
            arsort($counts);
            $topReason = key($counts);
        }

        if (!isset($cancelProductsArr[$productId])) {
            $cancelProductsArr[$productId] = [
                'product_id'    => $item->product_id,
                'product_name'  => $item->product_name,
                'product_image' => $item->product_image,
                'total_cancelled_qty' => 0,
                'total_cancelled_orders' => 0,
                'total_delivered_orders' => 0,
                'variants' => [],
                'last_cancelled' => $item->last_cancelled,
                'all_cancel_reasons' => [],
            ];
        }
        $cancelProductsArr[$productId]['total_cancelled_qty'] += $item->cancelled_qty;
        $cancelProductsArr[$productId]['total_cancelled_orders'] += $cancelledOrders;
        $cancelProductsArr[$productId]['total_delivered_orders'] += $deliveredOrders;
        $cancelProductsArr[$productId]['all_cancel_reasons'] = array_merge($cancelProductsArr[$productId]['all_cancel_reasons'], $reasonsArr);

        $cancelProductsArr[$productId]['variants'][] = [
            'variant_id'      => $item->variant_id,
            'variant_name'    => $item->variant_name,
            'cancelled_qty'   => $item->cancelled_qty,
            'cancelled_orders'=> $cancelledOrders,
            'delivered_orders'=> $deliveredOrders,
            'cancel_percent'  => $cancelPercent,
            'top_reason'      => $topReason,
            'last_cancelled'  => $item->last_cancelled,
        ];
    }
    foreach ($cancelProductsArr as &$prod) {
        $totalOrders = $prod['total_delivered_orders'] + $prod['total_cancelled_orders'];
        $prod['cancel_percent'] = $totalOrders > 0 ? round($prod['total_cancelled_orders'] * 100 / $totalOrders, 2) : 0;
        $reasonCounts = array_count_values($prod['all_cancel_reasons']);
        arsort($reasonCounts);
        $prod['top_reason'] = key($reasonCounts);
        unset($prod['all_cancel_reasons']);
    }
    unset($prod);

    $cancelledProducts = collect($cancelProductsArr)->sortByDesc('total_cancelled_orders')->take(10)->values()->toArray();

    // ==== Vùng miền (tuần/tháng/năm) ====
    $regionSalesQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('regions', 'products.region_id', '=', 'regions.id')
        ->where('orders.status', 'delivered');
    if ($regionType === 'week') {
        $regionSalesQuery->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($regionType === 'month') {
        $regionSalesQuery->whereMonth('orders.created_at', now()->month)->whereYear('orders.created_at', now()->year);
    } elseif ($regionType === 'year') {
        $regionSalesQuery->whereYear('orders.created_at', now()->year);
    }
    $regionSales = $regionSalesQuery
        ->select(
            'regions.name as region',
            DB::raw('SUM(order_items.total) as total_revenue'),
            DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
        )
        ->groupBy('regions.id', 'regions.name')
        ->orderByDesc('total_revenue')
        ->limit(6)
        ->get()
        ->map(function($item) {
            return [
                'region'        => $item->region,
                'total_revenue' => $item->total_revenue,
                'order_count'   => $item->order_count,
            ];
        })
        ->values();

    // Helper function
    $getTimeRange = function($type = 'week') {
        if ($type === 'today')  return [now()->startOfDay(), now()->endOfDay()];
        if ($type === 'week')   return [now()->startOfWeek(), now()->endOfWeek()];
        if ($type === 'month')  return [now()->startOfMonth(), now()->endOfMonth()];
        if ($type === 'year')   return [now()->startOfYear(), now()->endOfYear()];
        return [null, null];
    };

    // Lấy filter từng ô
    $topCustomerType        = $request->input('topCustomerFilter', 'week');
    $processingOrdersType   = $request->input('processingOrdersFilter', 'week');
    $topCategoryType        = $request->input('topCategoryFilter', 'week');
    $topSearchedProductType = $request->input('topSearchedProductFilter', 'week');

    // 1. Top khách hàng hoàn nhiều nhất (theo đơn hoàn thành)
    [$from, $to] = $getTimeRange($topCustomerType);
    $topCustomer = DB::table('orders')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->where('orders.status', 'delivered')
        ->when($from, fn($q) => $q->where('orders.created_at', '>=', $from))
        ->when($to,   fn($q) => $q->where('orders.created_at', '<=', $to))
        ->select('users.id', 'users.name',
                 DB::raw('COUNT(orders.id) as total_orders'),
                 DB::raw('SUM(orders.total_amount) as total_amount'))
        ->groupBy('users.id', 'users.name')
        ->orderByDesc('total_amount')
        ->orderByDesc('total_orders')
        ->first();

    // 2. Đơn cần xử lý (không phải delivered/cancelled/failed)
    [$from, $to] = $getTimeRange($processingOrdersType);
    $processingOrders = DB::table('orders')
        ->whereNotIn('status', ['delivered', 'cancelled', 'failed'])
        ->when($from, fn($q) => $q->where('created_at', '>=', $from))
        ->when($to,   fn($q) => $q->where('created_at', '<=', $to))
        ->count();

    // 3. Top danh mục bán chạy nhất (tổng sản phẩm đã bán qua đơn hoàn thành)
    [$from, $to] = $getTimeRange($topCategoryType);
    $topCategory = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->where('orders.status', 'delivered')
        ->when($from, fn($q) => $q->where('orders.created_at', '>=', $from))
        ->when($to,   fn($q) => $q->where('orders.created_at', '<=', $to))
        ->select('categories.id', 'categories.name',
                 DB::raw('SUM(order_items.quantity) as total_sold'))
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_sold')
        ->first();

    // 4. Sản phẩm được tìm kiếm nhiều nhất
    [$from, $to] = $getTimeRange($topSearchedProductType);
    $searchLog = DB::table('product_searches') // Đổi lại tên bảng nếu khác
        ->select('product_id', DB::raw('COUNT(*) as search_count'))
        ->when($from, fn($q) => $q->where('created_at', '>=', $from))
        ->when($to,   fn($q) => $q->where('created_at', '<=', $to))
        ->groupBy('product_id')
        ->orderByDesc('search_count')
        ->first();

    $topSearchedProduct = null;
    if ($searchLog) {
        $topSearchedProduct = \App\Models\Admin\Product::find($searchLog->product_id);
        if ($topSearchedProduct) $topSearchedProduct->search_count = $searchLog->search_count;
    }

    // ==== RETURN ====
   return view('backend.reports.dashboard', compact(
    'year', 'monthlyStats', 'newUsers',
    'fromDate', 'toDate',
    'totalCustomers', 'totalRevenue', 'completedOrders', 'totalRequests',
    // Xoá các biến growth không còn dùng nữa!
    'bestSellingProducts', 'type',
    'topRatedProducts', 'ratedType',
    'cancelledProducts', 'cancelledType',
    'regionSales', 'regionType',
    'donutStats',
    // Thêm 4 biến cho các ô dashboard
    'topCustomer', 'topCustomerType',
    'processingOrders', 'processingOrdersType',
    'topCategory', 'topCategoryType',
    'topSearchedProduct', 'topSearchedProductType'
));

}



  public function ajaxDashboard(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    $userQuery = User::query();
    $orderQuery = Order::query()->where('status', 'delivered');
    $ticketQuery = SupportTicket::query();

    if ($fromDate) {
        $userQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        $orderQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        $ticketQuery->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
    }
    if ($toDate) {
        $userQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        $orderQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        $ticketQuery->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
    }

    $totalCustomers = $userQuery->count();
    $completedOrders = $orderQuery->count();
    $totalRevenue = $orderQuery->sum('total_amount');
    $totalRequests = $ticketQuery->count();

    return response()->json([
        'totalCustomers'   => $totalCustomers,
        'completedOrders'  => $completedOrders,
        'totalRevenue'     => $totalRevenue,
        'totalRequests'    => $totalRequests,
    ]);
}

// public function yearlyData(Request $request)
public function yearlyData(Request $request)
{
    $year = $request->input('year', date('Y'));

    $monthlyStats = Order::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(total_amount) as revenue'),
        DB::raw('COUNT(id) as orders')
    )
    ->where('status', 'delivered')
    ->whereYear('created_at', $year)
    ->where('created_at', '<=', now()) // Thêm dòng này!
    ->groupBy(DB::raw('MONTH(created_at)'))
    ->orderBy('month')
    ->get();

    // Tổng doanh thu và đơn hàng của năm (theo dữ liệu đã lọc)
    $sumOrders = $monthlyStats->sum('orders');
    $sumRevenue = $monthlyStats->sum('revenue');

    return response()->json([
        'monthlyStats' => $monthlyStats,
        'year' => $year,
        'sumOrders' => $sumOrders,
        'sumRevenue' => $sumRevenue,
    ]);
}




// AJAX: top sản phẩm bán chạy filter tuần/tháng/năm
public function ajaxBestSellers(Request $request)
{
    $type = $request->input('type', 'week');
    $orderItemsQuery = \App\Models\Admin\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('product_variants', 'order_items.product_variant_value_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->where('orders.status', 'delivered');

    if ($type === 'week') {
        $orderItemsQuery->whereBetween('order_items.created_at', [
            \Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()
        ]);
    } elseif ($type === 'month') {
        $orderItemsQuery->whereMonth('order_items.created_at', \Carbon\Carbon::now()->month)
                        ->whereYear('order_items.created_at', \Carbon\Carbon::now()->year);
    } elseif ($type === 'year') {
        $orderItemsQuery->whereYear('order_items.created_at', \Carbon\Carbon::now()->year);
    }

    $bestSellingProducts = $orderItemsQuery
        ->select(
            'product_variants.id',
            'products.name as product_name',
            'product_variants.name as variant_name',
            'product_variants.image',
            'product_variants.price',
            'product_variants.stock',
            DB::raw('SUM(order_items.quantity) as sold_quantity'),
            DB::raw('MAX(order_items.created_at) as created_at')
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
        ->limit(50)
        ->get();

    return response()->json($bestSellingProducts);
}

// AJAX: top sản phẩm đánh giá cao
public function ajaxTopRatedProducts(Request $request)
{
    $type = $request->input('type', 'week');
    $reviewQuery = \App\Models\Admin\Review::join('products', 'reviews.product_id', '=', 'products.id')
        ->select(
            'products.id',
            'products.name as product_name',
            'products.image',
            DB::raw('AVG(reviews.rating) as average_rating'),
            DB::raw('COUNT(reviews.id) as review_count'),
            DB::raw('MAX(reviews.created_at) as created_at')
        );
    if ($type === 'week') {
        $reviewQuery->whereBetween('reviews.created_at', [
            \Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()
        ]);
    } elseif ($type === 'month') {
        $reviewQuery->whereMonth('reviews.created_at', \Carbon\Carbon::now()->month)
            ->whereYear('reviews.created_at', \Carbon\Carbon::now()->year);
    } elseif ($type === 'year') {
        $reviewQuery->whereYear('reviews.created_at', \Carbon\Carbon::now()->year);
    }

    $topRatedProducts = $reviewQuery
        ->groupBy('products.id', 'products.name', 'products.image')
        ->orderByDesc('average_rating')
        ->orderByDesc('review_count')
        ->limit(50)
        ->get();

    return response()->json($topRatedProducts);
}

// AJAX: top sản phẩm bị huỷ nhiều nhất
public function ajaxCancelledProducts(Request $request)
{
    $type = $request->input('type', 'week');
    $from = null; $to = null;
    if ($type === 'week') {
        $from = \Carbon\Carbon::now()->startOfWeek(); $to = \Carbon\Carbon::now()->endOfWeek();
    } elseif ($type === 'month') {
        $from = \Carbon\Carbon::now()->startOfMonth(); $to = \Carbon\Carbon::now()->endOfMonth();
    } elseif ($type === 'year') {
        $from = \Carbon\Carbon::now()->startOfYear(); $to = \Carbon\Carbon::now()->endOfYear();
    }

    // Lấy order_items bị huỷ (có biến thể)
    $cancelledItems = \App\Models\Admin\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->leftJoin('product_variants', 'order_items.product_variant_value_id', '=', 'product_variants.id')
        ->where('orders.status', 'cancelled')
        ->when($from, fn($q) => $q->whereDate('orders.created_at', '>=', $from))
        ->when($to, fn($q) => $q->whereDate('orders.created_at', '<=', $to))
        ->select(
            'products.id as product_id',
            'products.name as product_name',
            'products.image as product_image',
            'product_variants.id as variant_id',
            'product_variants.name as variant_name',
            DB::raw('SUM(order_items.quantity) as cancelled_qty'),
            DB::raw('COUNT(DISTINCT orders.id) as cancelled_orders'),
            DB::raw('MAX(orders.created_at) as last_cancelled'),
            DB::raw('GROUP_CONCAT(orders.cancel_reason) as cancel_reasons')
        )
        ->groupBy(
            'products.id',
            'products.name',
            'products.image',
            'product_variants.id',
            'product_variants.name'
        )
        ->get();

    // Lấy order_items giao thành công
    $deliveredItems = \App\Models\Admin\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'delivered')
        ->when($from, fn($q) => $q->whereDate('orders.created_at', '>=', $from))
        ->when($to, fn($q) => $q->whereDate('orders.created_at', '<=', $to))
        ->select(
            'order_items.product_id',
            'order_items.product_variant_value_id',
            DB::raw('COUNT(DISTINCT orders.id) as delivered_orders')
        )
        ->groupBy('order_items.product_id', 'order_items.product_variant_value_id')
        ->get()
        ->keyBy(fn($item) => $item->product_id . '-' . ($item->product_variant_value_id ?? 0));

    // Gom biến thể vào sản phẩm cha
    $cancelProductsArr = [];
    foreach ($cancelledItems as $item) {
        $productId = $item->product_id;
        $variantKey = $item->product_id . '-' . ($item->variant_id ?? 0);
        $deliveredOrders = isset($deliveredItems[$variantKey]) ? $deliveredItems[$variantKey]->delivered_orders : 0;
        $cancelledOrders = $item->cancelled_orders;
        $totalOrders = $deliveredOrders + $cancelledOrders;
        $cancelPercent = $totalOrders > 0 ? round($cancelledOrders * 100 / $totalOrders, 2) : 0;

        $reasonsArr = array_filter(explode(',', $item->cancel_reasons));
        $topReason = '';
        if (!empty($reasonsArr)) {
            $counts = array_count_values($reasonsArr);
            arsort($counts);
            $topReason = key($counts);
        }

        if (!isset($cancelProductsArr[$productId])) {
            $cancelProductsArr[$productId] = [
                'product_id'    => $item->product_id,
                'product_name'  => $item->product_name,
                'product_image' => $item->product_image,
                'total_cancelled_qty' => 0,
                'total_cancelled_orders' => 0,
                'total_delivered_orders' => 0,
                'variants' => [],
                'last_cancelled' => $item->last_cancelled,
                'all_cancel_reasons' => [],
            ];
        }
        $cancelProductsArr[$productId]['total_cancelled_qty'] += $item->cancelled_qty;
        $cancelProductsArr[$productId]['total_cancelled_orders'] += $cancelledOrders;
        $cancelProductsArr[$productId]['total_delivered_orders'] += $deliveredOrders;
        $cancelProductsArr[$productId]['all_cancel_reasons'] = array_merge($cancelProductsArr[$productId]['all_cancel_reasons'], $reasonsArr);

        $cancelProductsArr[$productId]['variants'][] = [
            'variant_id'      => $item->variant_id,
            'variant_name'    => $item->variant_name,
            'cancelled_qty'   => $item->cancelled_qty,
            'cancelled_orders'=> $cancelledOrders,
            'delivered_orders'=> $deliveredOrders,
            'cancel_percent'  => $cancelPercent,
            'top_reason'      => $topReason,
            'last_cancelled'  => $item->last_cancelled,
        ];
    }

    foreach ($cancelProductsArr as &$prod) {
        $totalOrders = $prod['total_delivered_orders'] + $prod['total_cancelled_orders'];
        $prod['cancel_percent'] = $totalOrders > 0 ? round($prod['total_cancelled_orders'] * 100 / $totalOrders, 2) : 0;
        $reasonCounts = array_count_values($prod['all_cancel_reasons']);
        arsort($reasonCounts);
        $prod['top_reason'] = key($reasonCounts);
        unset($prod['all_cancel_reasons']);
    }
    unset($prod);

    $cancelledProducts = collect($cancelProductsArr)->sortByDesc('total_cancelled_orders')->take(50)->values()->toArray();

    return response()->json($cancelledProducts);
}

// AJAX: doanh thu theo vùng miền
public function ajaxRegionSales(Request $request)
{
    $type = $request->input('type', 'week');
    $from = null; $to = null;
    if ($type === 'week') {
        $from = \Carbon\Carbon::now()->startOfWeek(); $to = \Carbon\Carbon::now()->endOfWeek();
    } elseif ($type === 'month') {
        $from = \Carbon\Carbon::now()->startOfMonth(); $to = \Carbon\Carbon::now()->endOfMonth();
    } elseif ($type === 'year') {
        $from = \Carbon\Carbon::now()->startOfYear(); $to = \Carbon\Carbon::now()->endOfYear();
    }

    $regionSales = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('regions', 'products.region_id', '=', 'regions.id')
        ->where('orders.status', 'delivered')
        ->when($from, fn($q) => $q->whereDate('orders.created_at', '>=', $from))
        ->when($to, fn($q) => $q->whereDate('orders.created_at', '<=', $to))
        ->selectRaw('
            regions.name as region,
            SUM(order_items.total) as total_revenue,
            COUNT(DISTINCT order_items.order_id) as order_count
        ')
        ->groupBy('regions.id', 'regions.name')
        ->orderByDesc('total_revenue')
        ->limit(6)
        ->get()
        ->map(function($item) {
            return [
                'region'        => $item->region,
                'total_revenue' => $item->total_revenue,
                'order_count'   => $item->order_count,
            ];
        })
        ->values();

    return response()->json($regionSales);
}

// AJAX: trạng thái đơn hàng (dùng donut chart)
public function ajaxDonutStats(Request $request)
{
    $type = $request->input('type', 'week');
    $now = now();

    $from = null; $to = null;

    if ($type === 'day') {
        $from = $now->copy()->startOfDay();
        $to = $now;
    } elseif ($type === 'week') {
        $from = $now->copy()->startOfWeek();
        $to = $now;
    } elseif ($type === 'month') {
        $from = $now->copy()->startOfMonth();
        $to = $now; // ✅ chỉ đến thời điểm hiện tại
    } elseif ($type === 'year') {
        $from = $now->copy()->startOfYear();
        $to = $now;
    }

    $completed = Order::where('status', 'delivered')
        ->when($from, fn($q) => $q->where('created_at', '>=', $from))
        ->when($to, fn($q) => $q->where('created_at', '<=', $to))
        ->count();

    $canceled = Order::where('status', 'cancelled')
        ->when($from, fn($q) => $q->where('created_at', '>=', $from))
        ->when($to, fn($q) => $q->where('created_at', '<=', $to))
        ->count();

    return response()->json([
        'completed' => $completed,
        'canceled' => $canceled,
    ]);
}

public function topCustomer(Request $request)
{
    $range = $this->getDateRange($request->type, true);
    $result = DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select('users.name', DB::raw('SUM(orders.total_amount) as total_amount'))
        ->whereBetween('orders.created_at', [$range['from'], $range['to']])
        ->where('orders.status', 'delivered')
        ->groupBy('users.id', 'users.name')
        ->orderByDesc('total_amount')
        ->first();

    return response()->json($result ?? ['name' => null, 'total_amount' => 0]);
}

    public function processingOrders(Request $request)
    {
        $range = $this->getDateRange($request->type);
        $count = DB::table('orders')
            ->whereBetween('created_at', [$range['from'], $range['to']])
            ->whereNotIn('status', ['cancelled', 'failed', 'delivered'])
            ->count();

        return response()->json(['count' => $count]);
    }

    public function topCategory(Request $request)
    {
        $range = $this->getDateRange($request->type, true);
        $result = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$range['from'], $range['to']])
            ->where('orders.status', 'delivered')
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->first();

        return response()->json($result ?? ['name' => null, 'total_sold' => 0]);
    }

public function topSearchedProduct(Request $request)
{
    $range = $this->getDateRange($request->type);
    $result = DB::table('product_searches')
        ->join('products', 'product_searches.product_id', '=', 'products.id')
        ->whereBetween('product_searches.created_at', [$range['from'], $range['to']])
        ->select('products.name', DB::raw('COUNT(*) as search_count'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('search_count')
        ->first();

    return response()->json($result ?? ['name' => null, 'search_count' => 0]);
}
private function getDateRange($type, $defaultWeek = false)
{
    $now = Carbon::now();
    $range = match ($type) {
        'today' => ['from' => $now->copy()->startOfDay(), 'to' => $now->copy()->endOfDay()],
        'week'  => ['from' => $now->copy()->startOfWeek(), 'to' => $now->copy()->endOfWeek()],
        'month' => ['from' => $now->copy()->startOfMonth(), 'to' => $now->copy()->endOfMonth()],
        'year'  => ['from' => $now->copy()->startOfYear(), 'to' => $now->copy()->endOfYear()],
        default => $defaultWeek
            ? ['from' => $now->copy()->startOfWeek(), 'to' => $now->copy()->endOfWeek()]
            : ['from' => $now->copy()->startOfDay(), 'to' => $now->copy()->endOfDay()],
    };

    // 👇 Ngăn ngày kết thúc vượt quá thời điểm hiện tại
    // if ($range['to']->greaterThan($now)) {
    //     $range['to'] = $now;
    // }

    return $range;
}


}
