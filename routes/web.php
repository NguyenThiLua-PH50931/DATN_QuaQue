<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\AttributeValueController as AdminAttributeValueController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\CommentController;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
//use App\Http\Controllers\Admin\ProductController as AdminProductController;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Client\ClientHomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController as GlobalProductController; // Nếu cần dùng controller gốc ngoài admin/client

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
        Route::post('/variant/{id}/toggle', [AdminProductController::class, 'toggleVariantStatus'])->name('variant.toggle');
        Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [AdminProductController::class, 'update'])->name('update');
        Route::post('/delete-image', [AdminProductController::class, 'deleteImage'])->name('deleteImage');
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
        Route::post('/{slug}/update', [AdminAttributeController::class, 'update'])->name('update');// Cập nhật
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


    // Blog
    Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
        Route::get('index', [BlogController::class, 'index'])->name('index');
        Route::get('create', [BlogController::class, 'create'])->name('create');
        Route::post('store', [BlogController::class, 'store'])->name('store');
        Route::get('show/{blog}', [BlogController::class, 'show'])->name('show');
        Route::get('edit/{blog}', [BlogController::class, 'edit'])->name('edit');
        Route::put('update/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('destroy/{blog}', [BlogController::class, 'destroy'])->name('destroy');
    });

    // Quản lý đánh giá
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
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
