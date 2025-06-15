<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
// use App\Http\Controllers\Client\ProductController;
// use App\Http\Controllers\Admin\RegionController;
// use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\AttributeValueController as AdminAttributeValueController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\CommentController;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\User\UserController;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\User\ProfileController;

use App\Http\Controllers\Admin\SupportTicketController;


use App\Http\Controllers\Client\ClientHomeController;

use App\Http\Controllers\Client\BlogController as ClientBlogController;
use App\Http\Controllers\Client\ClientSupportTicketController;

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController as GlobalProductController; // Nếu cần dùng controller gốc ngoài admin/client


// CLIENT
Route::get('/', function () {
    return redirect()->route('client.home');
});


Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    Route::get('home', [ClientHomeController::class, 'home'])->name('home');
    Route::get('product/{slug}', [ClientHomeController::class, 'show'])->name('product.detail');

    // Sản phẩm:
    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {});

    // Sản phẩm yêu thích
    Route::get('/wishlist', function () {
        return view('frontend.wishlist.wishlist');
    });

    // Liên hệ
   Route::prefix('support-ticket')->middleware('auth')->name('support-ticket.')->group(function () {
    Route::get('/', [ClientSupportTicketController::class, 'index'])->name('index');
    Route::get('/create', [ClientSupportTicketController::class, 'create'])->name('create');
    Route::post('/', [ClientSupportTicketController::class, 'store'])->name('store');
    Route::get('/{id}', [ClientSupportTicketController::class, 'show'])->name('show');
});

    // giỏ hàng
    Route::get('/cart', function () {
        return view('frontend.cart.cart');
    });

    //  Thanh toán
    Route::get('/checkout', function () {
        return view('frontend.checkout.checkout');
    });

    // Giỏ hàng:
    Route::middleware('auth')->group(function () {});

    // Thanh toán mua hàng:
    Route::middleware('auth')->group(function () {
        Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () {});
    });

    // Đơn hàng:
    Route::middleware('auth')->group(function () {
        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {});
    });
    // Support ticket (yêu cầu đăng nhập)
    // Route::middleware('auth')->group(function () {
    //     Route::get('support-ticket/create', [SupportTicketController::class, 'create'])->name('support-ticket.create');
    //     Route::post('support-ticket', [SupportTicketController::class, 'store'])->name('support-ticket.store');
    // });

});

//----------------------------------------------------------


// dang ky
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Đăng nhập
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'checklogin'])->name('checklogin');

// Các trang client tĩnh
Route::view('/wishlist', 'frontend.wishlist.wishlist')->name('wishlist');
Route::view('/compare', 'frontend.pages.compare')->name('compare');
Route::view('/contact', 'frontend.pages.contact');
Route::view('/cart', 'frontend.cart.cart');
Route::view('/checkout', 'frontend.checkout.checkout');
Route::view('/products/category', 'frontend.products.category');
Route::view('/seller/become-seller', 'frontend.seller.become-seller');
Route::view('/seller/seller-dashboard', 'frontend.seller.seller-dashboard');

// Blog
Route::get('/blog', [ClientBlogController::class, 'index'])->name('blog');
Route::get('/blog-detail/{id}', [ClientBlogController::class, 'show'])->name('blogs-detail');



//-----------------------------------------------------------------

// ADMIN:
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'checkAdmin'], function () {

    Route::get('home', [HomeController::class, 'home'])->name('home');

    // Route cho dashboard tổng quan và báo cáo
    Route::get('/reports', [ReportController::class, 'dashboard'])->name('dashboard');

    // Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
    //     Route::get('/', [ReportController::class, 'dashboard'])->name('dashboard');
    //     Route::get('/revenue', [ReportController::class, 'revenueByMonthYear'])->name('revenue');
    //     Route::get('/completed-orders', [ReportController::class, 'completedOrders'])->name('completed_orders');
    //     Route::get('/top-product', [ReportController::class, 'topProductRevenue'])->name('top_product');
    //     Route::get('/top-region', [ReportController::class, 'topRegionRevenue'])->name('top_region');
    //     Route::get('/new-users', [ReportController::class, 'newUsers'])->name('new_users');
    //     Route::get('/support-requests', [ReportController::class, 'supportRequests'])->name('support_requests');
    //     Route::get('/top-rated-product', [ReportController::class, 'topRatedProduct'])->name('top_rated_product');
    //     Route::get('/order-status', [ReportController::class, 'orderStatus'])->name('order_status');
    // });
    // Quản lý sản phẩm
    Route::post('/categories/store-quick', [AdminCategoryController::class, 'storeQuick'])->name('categories.storeQuick');
    Route::post('/regions/store-quick', [AdminRegionController::class, 'storeQuick'])->name('regions.storeQuick');
    Route::post('/attributes/store-quick', action: [AdminAttributeController::class, 'storeQuick'])->name('attributes.storeQuick');
    Route::post('/attribute-values/quick-store', [AdminAttributeValueController::class, 'storeQuick'])->name('attribute_values.storeQuick');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/store', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{slug}', [AdminProductController::class, 'show'])->name('show');
        Route::post('/{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('toggle');
        Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [AdminProductController::class, 'update'])->name('update');
        Route::post('/delete-image', [AdminProductController::class, 'deleteImage'])->name('deleteImage');
        Route::get('/{id}/description', [AdminProductController::class, 'getDescription'])->name('description');
        Route::get('/variant/{id}/description', [AdminProductController::class, 'getVariantDescription'])->name('variant.description');
        Route::post('/variant/{id}/toggle-status', [AdminProductController::class, 'toggleVariantStatus'])->name('variant.toggleStatus');
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
    // thuoc tinh
    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AdminAttributeController::class, 'index'])->name('index');               // Danh sách thuộc tính
        Route::get('/create', [AdminAttributeController::class, 'create'])->name('create');       // Form tạo mới
        Route::post('/store', [AdminAttributeController::class, 'store'])->name('store');         // Lưu mới
        Route::get('/{slug}', [AdminAttributeController::class, 'show'])->name('show');           // Xem chi tiết
        Route::get('/{slug}/edit', [AdminAttributeController::class, 'edit'])->name('edit');      // Form chỉnh sửa
        Route::post('/{slug}/update', [AdminAttributeController::class, 'update'])->name('update'); // Cập nhật
        Route::delete('/{id}', [AdminAttributeController::class, 'destroy'])->name('destroy');    // Xóa
        Route::post('/bulk-delete', [AdminAttributeController::class, 'bulkDelete'])->name('bulkDelete'); // Xóa nhiều
        Route::post('/{id}/toggle', [AdminAttributeController::class, 'toggleStatus'])->name('toggle'); // Toggle trạng thái (nếu dùng)
        Route::post('/variant/{id}/toggle', [AdminAttributeController::class, 'toggleVariantStatus'])->name('variant.toggle'); // Toggle variant status
    });
    // Categories
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');                  // danh sách categories (trang admin)
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');                 // thêm mới
        Route::put('{id}', [AdminCategoryController::class, 'update'])->name('update');             // cập nhật
        Route::get('create', [AdminCategoryController::class, 'create'])->name('create');           // form tạo
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
        Route::get('{id}/edit', [BannerController::class, 'edit'])->name('edit');
        Route::delete('{id}/soft', [BannerController::class, 'softDelete'])->name('softDelete');
        Route::delete('{id}/force', [BannerController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/restore', [BannerController::class, 'restore'])->name('restore');
        Route::get('trashed', [BannerController::class, 'trashed'])->name('trashed');
        Route::get('{id}', [BannerController::class, 'show'])->name('show');
        Route::delete('bulk-delete', [BannerController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('bulk-force-delete', [BannerController::class, 'bulkForceDelete'])->name('bulkForceDelete');
        Route::post('bulk-restore', [BannerController::class, 'bulkRestore'])->name('bulkRestore');
    });

    // Order
    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/tracking', [OrderController::class, 'tracking'])->name('tracking');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
        Route::put('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('updateStatus');
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
    Route::post('/attributes/store-quick', [AdminAttributeController::class, 'storeQuick'])->name('attributes.storeQuick');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/store', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{slug}', [AdminProductController::class, 'show'])->name('show');
        Route::post('/{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('toggle');
        Route::post('/variant/{id}/toggle', [AdminProductController::class, 'toggleVariantStatus'])->name('variant.toggle');
        Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [AdminProductController::class, 'update'])->name('update');
        Route::post('/delete-image', [AdminProductController::class, 'deleteImage'])->name('deleteImage');
    });

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
    });
});
