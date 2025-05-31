<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;

use App\Http\Controllers\Client\ClientHomeController;
use App\Http\Controllers\Client\ProductController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController as GlobalProductController; // Nếu cần dùng controller gốc ngoài admin/client


// Route::get('/', [ProductController::class, 'home'])->name('frontend.home');
// Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

// Đăng ký người dùng

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
    Route::get('/contact', function () {
        return view('frontend.pages.contact');
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

// // ==================
// // ADMIN ROUTES
// // ==================
// Route::prefix('admin')->name('admin.')->group(function () {

//     // Dashboard
//     Route::view('/', 'backend.dashboard')->name('dashboard');

//     // Sản phẩm
//     Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
//     Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
//     Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');

//     // Danh mục
//     Route::view('/categories', 'backend.categories.index')->name('categories.index');
//     Route::view('/categories/create', 'backend.categories.create')->name('categories.create');

//     // Thuộc tính sản phẩm
//     Route::view('/attributes', 'backend.attributes.index')->name('attributes.index');
//     Route::view('/attributes/create', 'backend.attributes.create')->name('attributes.create');

//     // Người dùng
//     Route::view('/users', 'backend.users.index')->name('users.index');
//     Route::view('/users/create', 'backend.users.create')->name('users.create');

//     // Vai trò
//     Route::view('/roles', 'backend.roles.index')->name('roles.index');
//     Route::view('/roles/create', 'backend.roles.create')->name('roles.create');

//     // Media
//     Route::view('/media', 'backend.media.index')->name('media.index');

//     // Quản lý đơn hàng
//     Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
//     Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
//     Route::get('/orders/{order}/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
//     Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
//     Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

//     // Phiếu giảm giá
//     Route::view('/coupons', 'backend.coupons.index')->name('coupons.index');
//     Route::view('/coupons/create', 'backend.coupons.create')->name('coupons.create');

//     // Thuế
//     Route::view('/taxes', 'backend.taxes.index')->name('taxes.index');

//     // Đánh giá sản phẩm
//     Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');  // Thêm route cho reviews

//     // Yêu cầu hỗ trợ
//     Route::view('/support-ticket', 'backend.support-ticket.index')->name('support-ticket.index');

//     // Cài đặt hồ sơ
//     Route::view('/profile-setting', 'backend.profile-setting.index')->name('profile-setting.index');

//     // Báo cáo
//     Route::view('/reports', 'backend.reports.index')->name('reports.index');

//     // Trang danh sách
//     Route::view('/list-page', 'backend.list-page.index')->name('list-page.index');





//-----------------------------------------------------------------

// ADMIN:
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'checkAdmin'], function () {

    Route::get('home', [HomeController::class, 'home'])->name('home');
    // Product
    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        // // product
        // Route::get('/admin/products', function () {
        //     return view('backend.products.index');
        // });

        // Route::get('/admin/products/create', function () {
        //     return view('backend.products.create');
        // });
    });

    // Category
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        // Route::get('/admin/categories', function () {
        //     return view('backend.categories.index');
        // });
        // Route::get('/admin/categories/create', function () {
        //     return view('backend.categories.create');
        // });
        // // Attributes
        // Route::get('/admin/attributes', function () {
        //     return view('backend.attributes.index');
        // });
        // Route::get('/admin/attributes/create', function () {
        //     return view('backend.attributes.create');
        // });
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
        // Thêm mới
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        // Ẩn hiện tài khoản:
        Route::get('toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('hidden', [UserController::class, 'hidden'])->name('hidden');
        // Xóa tài khoản:
        Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
        // // roles
        // Route::get('/admin/roles', function () {
        //     return view('backend.roles.index');
        // });
        // Route::get('/admin/roles/create', function () {
        //     return view('backend.roles.create');
        // });
    });

    // comments

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    Route::post('/comments/{id}/reply', [CommentController::class, 'reply'])->name('comments.reply');
});



// // media
// Route::get('/admin/media', function () {
//     return view('backend.media.index');
// });
// // order
// Route::get('/admin/orders', function () {
//     return view('backend.orders.index');
// });
// Route::get('/admin/orders/detail', function () {
//     return view('backend.orders.show');
// });
// Route::get('/admin/orders/tracking', function () {
//     return view('backend.orders.tracking');
// });
// // coupons
// Route::get('/admin/coupons', function () {
//     return view('backend.coupons.index');
// });
// Route::get('/admin/coupons/create', function () {
//     return view('backend.coupons.create');
// });
// // tax
// Route::get('/admin/taxes', function () {
//     return view('backend.taxes.index');
// });
// // product-review
// // Route::get('/admin/product-review', function () {
// //     return view('backend.product-review.index');
// // });
// // support-ticket
// Route::get('/admin/support-ticket', function () {
//     return view('backend.support-ticket.index');
// });
// // profile-setting
// Route::get('/admin/profile-setting', function () {
//     return view('backend.profile-setting.index');
// });
// // reports
// Route::get('/admin/reports', function () {
//     return view('backend.reports.index');
// });
// // list-page
// Route::get('/admin/list-page', function () {
//     return view('backend.list-page.index');
// });

// Admin
// quan li san pham
Route::prefix('admin')->name('admin.')->group(function () {

    // Quản lý đánh giá
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // Quản lý sản phẩm
    Route::post('/categories/store-quick', [AdminCategoryController::class, 'storeQuick'])->name('categories.storeQuick');
    Route::post('/regions/store-quick', [AdminRegionController::class, 'storeQuick'])->name('regions.storeQuick');

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
    });
});