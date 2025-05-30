<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Client\ClientHomeController;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

// CLIENT

Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    Route::get('home', [ClientHomeController::class, 'home'])->name('home');

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
// dang nhap
Route::get('/login', [LoginController::class, 'login'])->name('Login');
Route::post('/login', [LoginController::class, 'checklogin'])->name('checklogin');




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
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {});

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
Route::prefix('admin')->group(function () {
    // Quản lý đánh giá
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    // product
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::get('/products/{slug}', [AdminProductController::class, 'show'])->name('products.show');
    Route::post('/admin/products/{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('admin.products.toggle');
    Route::post('/admin/variants/{id}/toggle', [AdminProductController::class, 'toggleVariantStatus'])->name('admin.variants.toggle');
    Route::post('/admin/products/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('admin.products.bulkDelete');
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
});
