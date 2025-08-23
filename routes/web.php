<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
// use App\Http\Controllers\Client\ProductController;
// use App\Http\Controllers\Admin\RegionController;
// use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AttributeValueController as AdminAttributeValueController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\User\ProfileController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\ClientHomeController;
use App\Http\Controllers\Client\BlogController as ClientBlogController;
use App\Http\Controllers\Client\CartController;

use App\Http\Controllers\Client\BlogCommentController as ClientBlogCommentController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use App\Http\Controllers\Client\ClientSupportTicketController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\ForgotController;
use App\Http\Controllers\Client\ProfileClientController;
use App\Http\Controllers\Client\ResetPasswordController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Client\OrdersController;
use App\Http\Controllers\Client\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\ChatbotController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Nếu cần dùng controller gốc ngoài admin/client thì khai báo use ở đây
// use App\Http\Controllers\ProductController as GlobalProductController;

// ================== CLIENT ==================

// Route mặc định chuyển hướng đến /client/home
Route::redirect('/', '/client/home');

Route::group(['prefix' => 'client', 'as' => 'client.'], function () {

    // ================== PAYMENT CALLBACKS ==================
    Route::post('/zalopay/ipn', [PaymentController::class, 'zalopayIpn'])
        ->name('zalopay.ipn')
        ->withoutMiddleware([VerifyCsrfToken::class]);

    // ================== HOME ==================
    Route::get('home', [ClientHomeController::class, 'home'])->name('home');

    // ================== ĐÁNH GIÁ ==================
    Route::group(['prefix' => 'danh-gia', 'as' => 'review.'], function () {
        Route::post('store', [ClientReviewController::class, 'store'])->name('store');
    });

    // ================== AI CHAT BOT ==================
    Route::post('chatbot', [ChatbotController::class, 'chat'])->name('chatbot.send');

    // ================== SẢN PHẨM ==================
    Route::group(['prefix' => 'san-pham', 'as' => 'product.'], function () {
        Route::get('/', [ClientProductController::class, 'catalog'])->name('catalog');
        Route::get('/all', [ClientProductController::class, 'index'])->name('index');
        Route::get('/catalog/ajax', [ClientProductController::class, 'catalogAjax'])->name('catalog.ajax');
        Route::get('/search', [AdminProductController::class, 'searchPage'])->name('search');
        Route::get('/search-ajax', [ClientProductController::class, 'searchAjax'])->name('searchAjax');
        Route::get('/{slug}/reviews', [ClientProductController::class, 'filterReviews'])->name('reviews.filter');
        Route::get('/{slug}', [ClientProductController::class, 'show'])->name('detail');
        Route::post('/get-variant', [ClientProductController::class, 'getVariant'])->name('.getVariant');
        Route::post('/reviews', [ClientProductController::class, 'storeReview'])->name('reviews.store');
        Route::get('/quickview/{slug}', [ClientProductController::class, 'quickView'])->name('quickview');
        // Bình luận sản phẩm
        Route::post('/san-pham/{product}/comment', [ClientProductController::class, 'comment'])->name('comment.store');
        Route::post('comment/{comment}/reply', [ClientProductController::class, 'commentReply'])->name('comment.reply');
        Route::delete('/comment/{comment}', [ClientProductController::class, 'deleteComment'])->name('comment.destroy');
        Route::delete('/reply/{reply}', [ClientProductController::class, 'deleteReply'])->name('reply.destroy');
    });

    // ================== WISHLIST ==================
    Route::middleware('auth')->group(function () {
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::delete('/wishlist/{product_id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    });

    // ================== SUPPORT TICKET ==================
    Route::prefix('support-ticket')->middleware('auth')->name('support-ticket.')->group(function () {
        Route::get('/', [ClientSupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [ClientSupportTicketController::class, 'create'])->name('create');
        Route::post('/', [ClientSupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [ClientSupportTicketController::class, 'show'])->name('show');
    });

    // ================== CART ==================
    Route::prefix('cart')->middleware('auth')->name('cart.')->group(function () {
        Route::get('index', [CartController::class, 'index'])->name('index');
        Route::post('add', [CartController::class, 'add'])->name('add');
        Route::delete('delete/{id}', [CartController::class, 'delete'])->name('delete');
        Route::post('/store-quick', [CartController::class, 'storeQuick'])->name('storeQuick');
        Route::post('bulkDelete', [CartController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('updateQuantity', [CartController::class, 'updateQuantity'])->name('updateQuantity');
        Route::delete('remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('update-variant', [CartController::class, 'updateVariant'])->name('updateVariant');
        Route::post('checkout-selected', [CartController::class, 'proceedCheckout'])->name('proceedCheckout');
        Route::post('check-stock', [CartController::class, 'checkStock'])->name('checkStock');
    });

    // ================== CHECKOUT + PAYMENT ==================
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
        Route::post('/checkout/process', [CheckoutController::class, 'processOrder'])->name('checkout.process');
        Route::post('/checkout/address/save', [CheckoutController::class, 'saveAddress'])->name('checkout.address.save');
        Route::post('/checkout/address/{id}/update', [CheckoutController::class, 'updateAddress'])->name('checkout.address.update');
        Route::post('/checkout/shipping-method', [CheckoutController::class, 'updateShippingMethod'])->name('checkout.updateShippingMethod');
        Route::post('/checkout/apply-discount', [CheckoutController::class, 'applyDiscount'])->name('checkout.applyDiscount');
        Route::post('/checkout/remove-discount', [CheckoutController::class, 'removeDiscount'])->name('checkout.removeDiscount');
        Route::post('/checkout/bank-confirm', [CheckoutController::class, 'bankConfirm'])->name('client.checkout.bankConfirm');
        Route::post('/checkout/update-pending-payment-address', [CheckoutController::class, 'updatePendingPaymentAddress'])->name('checkout.updatePendingPaymentAddress');
        Route::post('/checkout/check-discount-before-momo', [CheckoutController::class, 'checkDiscountBeforeMomo'])->name('checkout.checkDiscountBeforeMomo');

        // Payment create-order endpoints
        Route::post('/pay/momo', [PaymentController::class, 'payWithMomo'])->name('pay.momo');
        Route::post('/pay/zalopay', [PaymentController::class, 'payWithZaloPay'])->name('pay.zalopay');

        // Orders
        Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/orders/status-bulk', [OrdersController::class, 'statusBulk'])->name('orders.statusBulk');
        Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [OrdersController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/status', [OrdersController::class, 'orderStatus'])->name('orders.status');
    });

    // ================== CHECKOUT SUCCESS ==================
    Route::get('/checkout/success', function () {
        return view('frontend.checkout.checkoutsuccess');
    })->name('checkout.success');

    // ================== SAVE ADDRESS SNAPSHOT ==================
    Route::post('/checkout/save-address-snapshot', function (Request $request) {
        session([
            'address_snapshot' => [
                'recipient_name' => $request->input('recipient_name'),
                'phone'          => $request->input('phone'),
                'address'        => $request->input('address'),
                'province'       => $request->input('province'),
                'district'       => $request->input('district'),
                'ward'           => $request->input('ward'),
            ]
        ]);
        return response()->json(['ok' => true]);
    })->name('checkout.saveAddressSnapshot');

    // ================== LIÊN HỆ ==================
    Route::get('lienhe', [ContactController::class, 'lienhe'])->name('lienhe');
    Route::post('lienhe', [ContactController::class, 'submit'])->name('submit');

    // ================== BLOG ==================
    Route::get('/blog', [ClientBlogController::class, 'index'])->name('blog');
    Route::get('/blog-detail/{id}', [ClientBlogController::class, 'show'])->name('blogs-detail');
    Route::middleware('auth')->post('/blog/comments', [ClientBlogCommentController::class, 'store'])->name('blog.comments.store');

    // ================== ABOUT ==================
    Route::get('/about', [AboutController::class, 'index'])->name('about');
});

// ================== PROFILE CLIENT ==================
Route::middleware('auth')->group(function () {
    Route::get('/index', [ProfileClientController::class, 'index'])->name('index');
    Route::put('/update', [ProfileClientController::class, 'update'])->name('update');
});

// ================== AUTH & PASSWORD ==================
Route::get('forgot', [ForgotController::class, 'forgot'])->name('forgot');
Route::post('forgot', [ForgotController::class, 'sendResetLink'])->name('sendResetLink');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'checklogin'])->name('checklogin');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/save-shipping-method', function (Request $request) {
    session(['shipping_method' => (int) $request->shipping_method]);
    return response()->json(['success' => true]);
});

// route api real-time admin/orders
Route::get('/admin/orders/latest-id', [App\Http\Controllers\Admin\OrderController::class, 'latestOrderId']);

// ADMIN:
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'checkAdmin'], function () {

    // Dashboard chính
    Route::get('/reports', [ReportController::class, 'dashboard'])->name('dashboard');

    // AJAX báo cáo theo từng phần
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/top-customer', [ReportController::class, 'topCustomer'])->name('topCustomer');
        Route::get('/processing-orders', [ReportController::class, 'processingOrders'])->name('processingOrders');
        Route::get('/top-category', [ReportController::class, 'topCategory'])->name('topCategory');
        Route::get('/top-searched-product', [ReportController::class, 'topSearchedProduct'])->name('topSearchedProduct');
        Route::get('/top-customers', [ReportController::class, 'ajaxTopCustomers'])->name('topCustomers');

        // Các route thêm khác (giữ lại nếu cần thiết)
        Route::get('/top-selling-products', [ReportController::class, 'topSellingProducts'])->name('topSellingProducts');
        Route::get('/yearly-data', [ReportController::class, 'yearlyData'])->name('yearlyData');
        Route::get('/best-sellers', [ReportController::class, 'ajaxBestSellers'])->name('bestSellersAjax');
        Route::get('/top-rated-products', [ReportController::class, 'ajaxTopRatedProducts'])->name('topRatedProductsAjax');
        Route::get('/cancelled-products', [ReportController::class, 'ajaxCancelledProducts'])->name('cancelledProductsAjax');
        Route::get('/region-sales', [ReportController::class, 'ajaxRegionSales'])->name('regionSalesAjax');
        Route::get('/donut-stats', [ReportController::class, 'ajaxDonutStats'])->name('donutStatsAjax');
        Route::get('/ajax', [ReportController::class, 'ajaxDashboard'])->name('ajaxDashboard');
    });
    // Quản lý sản phẩm
    Route::post('/categories/store-quick', [AdminCategoryController::class, 'storeQuick'])->name('categories.storeQuick');
    Route::post('/regions/store-quick', [AdminRegionController::class, 'storeQuick'])->name('regions.storeQuick');
    Route::post('/attributes/store-quick', action: [AttributeController::class, 'storeQuick'])->name('attributes.storeQuick');
    Route::post('/attribute-values/quick-store', [AdminAttributeValueController::class, 'storeQuick'])->name('attribute_values.storeQuick');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/search', [AdminProductController::class, 'search']);
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/store', [AdminProductController::class, 'store'])->name('store');
        Route::post('/{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('toggle');
        Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/image/{id}', [AdminProductController::class, 'deleteImage'])->name('image.delete');
        Route::get('/check-auto-delete-status', [AdminProductController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
        Route::get('/{id}/description', [AdminProductController::class, 'getDescription'])->name('description');
        Route::get('/variant/{id}/description', [AdminProductController::class, 'getVariantDescription'])->name('variant.description');
        Route::post('/variant/{id}/toggle-status', [AdminProductController::class, 'toggleVariantStatus'])->name('variant.toggleStatus');
        Route::post('/variant/{id}/update-stock', [AdminProductController::class, 'updateVariantStock'])->name('variant.updateStock');
        Route::get('/trashed', [AdminProductController::class, 'trashed'])->name('trashed');
        Route::get('/{slug}', [AdminProductController::class, 'show'])->name('show');
        Route::post('/bulk-restore', [AdminProductController::class, 'bulkRestore'])->name('bulkRestore');
        Route::post('/bulk-force-delete', [AdminProductController::class, 'bulkForceDelete'])->name('bulkForceDelete');
        Route::post('/{id}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force', [AdminProductController::class, 'forceDelete'])->name('forceDelete');
    });

    // bien the
    Route::prefix('products/variant')->name('products.variant.')->group(function () {
        Route::get('/{productId}', [AdminProductVariantController::class, 'index'])->name('index'); // Danh sách biến thể của sản phẩm
        Route::get('/{productId}/create', [AdminProductVariantController::class, 'create'])->name('create');
        Route::post('/{productId}/store', [AdminProductVariantController::class, 'store'])->name('store');
        Route::get('/show/{id}', [AdminProductVariantController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [AdminProductVariantController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [AdminProductVariantController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminProductVariantController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [AdminProductVariantController::class, 'bulkDelete'])->name('bulkDelete');
    });
    // Attibute
    Route::group(['prefix' => 'attributes', 'as' => 'attributes.'], function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');                  // danh sách thuộc tính
        Route::post('/', [AttributeController::class, 'store'])->name('store');                 // thêm mới
        Route::get('create', [AttributeController::class, 'create'])->name('create');           // form tạo
        Route::get('/check-auto-delete-status', [AttributeController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
        Route::get('/{slug}/edit', [AttributeController::class, 'edit'])->name('edit'); // Form chỉnh sửa
        Route::put('/{slug}', [AttributeController::class, 'update'])->name('update');   // Cập nhật     // Cập nhật (dùng PUT để nhất quán với REST)
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('destroy');       // Xóa mềm một thuộc tính (sử dụng destroy)
        Route::post('/bulk-delete', [AttributeController::class, 'bulkDelete'])->name('bulkDelete'); // Xóa mềm nhiều thuộc tính
        Route::get('/trashed', [AttributeController::class, 'trashed'])->name('trashed');        // Danh sách thuộc tính đã xóa mềm
        Route::post('/{id}/restore', [AttributeController::class, 'restore'])->name('restore');  // Khôi phục một thuộc tính
        Route::post('/bulk-restore', [AttributeController::class, 'bulkRestore'])->name('bulkRestore'); // Khôi phục nhiều thuộc tính
        Route::delete('/{id}/force', [AttributeController::class, 'forceDelete'])->name('forceDelete');  // Xóa vĩnh viễn một thuộc tính
        Route::post('/bulk-force-delete', [AttributeController::class, 'bulkForceDelete'])->name('bulkForceDelete'); // Xóa vĩnh viễn nhiều thuộc tính
    });

    // Categories
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');                  // danh sách categories (trang admin)
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');                 // thêm mới
        Route::put('{id}', [AdminCategoryController::class, 'update'])->name('update');             // cập nhật
        Route::get('create', [AdminCategoryController::class, 'create'])->name('create');           // form tạo
        Route::get('check-auto-delete-status', [AdminCategoryController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
        Route::get('{id}/edit', [AdminCategoryController::class, 'edit'])->name('edit');            // form sửa
        Route::delete('{id}/trashed', [AdminCategoryController::class, 'softDelete'])->name('softDelete');  // xóa mềm
        Route::delete('{id}/force', [AdminCategoryController::class, 'forceDelete'])->name('forceDelete');  // xóa cứng
        Route::post('{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');  // khôi phục
        Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');        // danh sách đã xóa mềm
        Route::delete('bulk-delete', [AdminCategoryController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('bulk-force-delete', [AdminCategoryController::class, 'bulkForceDelete'])->name('bulkForceDelete');
        Route::post('bulk-restore', [AdminCategoryController::class, 'bulkRestore'])->name('bulkRestore');
    });

    // Regions
    Route::group(['prefix' => 'regions', 'as' => 'regions.'], function () {
        Route::get('/', [AdminRegionController::class, 'index'])->name('index');
        Route::post('/', [AdminRegionController::class, 'store'])->name('store');
        Route::put('{id}', [AdminRegionController::class, 'update'])->name('update');
        Route::get('create', [AdminRegionController::class, 'create'])->name('create');
        Route::get('check-auto-delete-status', [AdminRegionController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
        Route::get('{id}/edit', [AdminRegionController::class, 'edit'])->name('edit');
        Route::delete('{id}/soft', [AdminRegionController::class, 'softDelete'])->name('softDelete');
        Route::delete('{id}/force', [AdminRegionController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/restore', [AdminRegionController::class, 'restore'])->name('restore');
        Route::get('trashed', [AdminRegionController::class, 'trashed'])->name('trashed');
        Route::delete('bulk-delete', [AdminRegionController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('bulk-force-delete', [AdminRegionController::class, 'bulkForceDelete'])->name('bulkForceDelete');
        Route::post('bulk-restore', [AdminRegionController::class, 'bulkRestore'])->name('bulkRestore');
    });

    // Banners
    Route::group(['prefix' => 'banners', 'as' => 'banners.'], function () {
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::post('/', [BannerController::class, 'store'])->name('store');
        Route::put('{id}', [BannerController::class, 'update'])->name('update');
        Route::get('create', [BannerController::class, 'create'])->name('create');
        Route::get('check-auto-delete-status', [BannerController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
        Route::get('{id}/edit', [BannerController::class, 'edit'])->name('edit');
        Route::delete('{id}/soft', [BannerController::class, 'softDelete'])->name('softDelete');
        Route::delete('{id}/force', [BannerController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/restore', [BannerController::class, 'restore'])->name('restore');
        Route::get('trashed', [BannerController::class, 'trashed'])->name('trashed');
        Route::delete('bulk-delete', [BannerController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('bulk-force-delete', [BannerController::class, 'bulkForceDelete'])->name('bulkForceDelete');
        Route::post('bulk-restore', [BannerController::class, 'bulkRestore'])->name('bulkRestore');
        Route::get('{id}', [BannerController::class, 'show'])->name('show');
    });

    // Order
Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
        // ĐẶT CÁC ROUTE CỤ THỂ LÊN TRƯỚC
        Route::put('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');

        // ✅ Route hủy đơn cho admin (method adminCancel trong controller)
        Route::post('/{order}/cancel', [OrderController::class, 'adminCancel'])->name('cancel');

        Route::get('/{order}/tracking', [OrderController::class, 'tracking'])->name('tracking');

        // (Tuỳ bạn) — nếu đã xoá hẳn function hide() thì bỏ route này đi cho sạch:
        // Route::patch('/{order}/hide', [OrderController::class, 'hide'])->name('hide');

        // CÁC ROUTE “BẮT TỔNG” ĐỂ SAU CÙNG
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });


    // User
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('index', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('hidden', [UserController::class, 'hidden'])->name('hidden');
        Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
        // Chỉnh sửa
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [UserController::class, 'update'])->name('update');
    });

    // Comments

    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/reply', [CommentController::class, 'reply'])->name('reply');
        Route::post('/{id}/reply', [CommentController::class, 'storeReply'])->name('storeReply');
        Route::get('/{commentId}/reply/{replyId}/edit', [CommentController::class, 'editReply'])->name('editReply');
        Route::put('/{commentId}/reply/{replyId}', [CommentController::class, 'updateReply'])->name('updateReply');
        Route::delete('/{commentId}/reply/{replyId}', [CommentController::class, 'destroyReply'])->name('destroyReply');
        Route::post('/{id}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::get('/trashed', [CommentController::class, 'trashed'])->name('trashed');
        Route::get('/check-auto-delete-status', [CommentController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
    });

    // SupportTicket
    Route::prefix('support-ticket')->name('support-ticket.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [SupportTicketController::class, 'storeReply'])->name('storeReply');
        Route::delete('/{id}', [SupportTicketController::class, 'destroy'])->name('destroy');
    });


    // Blog
    Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
        Route::get('index', [BlogController::class, 'index'])->name('index');
        Route::get('create', [BlogController::class, 'create'])->name('create');
        Route::post('store', [BlogController::class, 'store'])->name('store');
        Route::get('check-auto-delete-status', [BlogController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');
        Route::get('show/{blog}', [BlogController::class, 'show'])->name('show');
        Route::get('edit/{blog}', [BlogController::class, 'edit'])->name('edit');
        Route::put('update/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('destroy/{blog}', [BlogController::class, 'softDelete'])->name('softDelete');
        Route::delete('{id}/force', [BlogController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/restore', [BlogController::class, 'restore'])->name('restore');
        Route::get('trashed', [BlogController::class, 'trashed'])->name('trashed');
        Route::get('{id}', [BlogController::class, 'show'])->name('show');
        Route::delete('bulk-delete', [BlogController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('bulk-force-delete', [BlogController::class, 'bulkForceDelete'])->name('bulkForceDelete');
        Route::post('bulk-restore', [BlogController::class, 'bulkRestore'])->name('bulkRestore');
    });

    // Quản lý đánh giá
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
        // Route::get('/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        // Route::post('/{id}/reply', [ReviewController::class, 'storeReply'])->name('reviews.storeReply');
        // Route::get('/{reviewId}/reply/{replyId}/edit', [ReviewController::class, 'editReply'])->name('reviews.editReply');
        // Route::put('/{reviewId}/reply/{replyId}', [ReviewController::class, 'updateReply'])->name('reviews.updateReply');
        // Route::delete('/{reviewId}/reply/{replyId}', [ReviewController::class, 'destroyReply'])->name('reviews.destroyReply');
    });


    // Quản lý sản phẩm
    Route::post('/categories/store-quick', [AdminCategoryController::class, 'storeQuick'])->name('categories.storeQuick');
    Route::post('/regions/store-quick', [AdminRegionController::class, 'storeQuick'])->name('regions.storeQuick');
    //Route::post('/attributes/store-quick', [AdminAttributeController::class, 'storeQuick'])->name('attributes.storeQuick');

    // Route::prefix('products')->name('products.')->group(function () {
    //     Route::get('/', [AdminProductController::class, 'index'])->name('index');
    //     Route::get('/create', [AdminProductController::class, 'create'])->name('create');
    //     Route::post('/store', [AdminProductController::class, 'store'])->name('store');
    //     Route::get('/{slug}', [AdminProductController::class, 'show'])->name('show');
    //     Route::post('/{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('toggle');
    //     Route::post('/variant/{id}/toggle', [AdminProductController::class, 'toggleVariantStatus'])->name('variant.toggle');
    //     Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulkDelete');
    //     Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
    //     Route::get('/{slug}/edit', [AdminProductController::class, 'edit'])->name('edit');
    //     Route::post('/{slug}/update', [AdminProductController::class, 'update'])->name('update');
    //     Route::delete('/image/{id}', [AdminProductController::class, 'deleteImage'])->name('image.delete');
    // });

    // Chỉnh sửa hồ sơ:
    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('update');
    });

    // Mã giảm giá
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('index', [CouponsController::class, 'index'])->name('index');
        Route::get('create', [CouponsController::class, 'create'])->name('create');
        Route::post('store', [CouponsController::class, 'store'])->name('store');
        Route::delete('destroy/{id}', [CouponsController::class, 'destroy'])->name('destroy');
        Route::get('edit/{id}', [CouponsController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [CouponsController::class, 'update'])->name('update');
        Route::get('check-auto-delete-status', [CouponsController::class, 'checkAutoDeleteStatus'])->name('checkAutoDeleteStatus');

         Route::get('trashed',           [CouponsController::class, 'trashed'])->name('trashed');
    Route::put('restore/{id}',      [CouponsController::class, 'restore'])->name('restore');
    Route::delete('force-delete/{id}', [CouponsController::class, 'forceDelete'])->name('force-delete');
    });
    Route::get('/api/provinces', [LocationController::class, 'provinces']);
    Route::get('/api/districts', [LocationController::class, 'districts']);
    Route::get('/api/wards', [LocationController::class, 'wards']);
});
