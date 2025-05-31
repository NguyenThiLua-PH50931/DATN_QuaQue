<?php


use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CommentController;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;

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
    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('create', [AdminProductController::class, 'create'])->name('products.create');
        Route::get('{slug}', [AdminProductController::class, 'show'])->name('products.show');
        Route::post('{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('admin.products.toggle');
Route::post('variants/{id}/toggle', [AdminProductController::class, 'toggleVariantStatus'])->name('admin.variants.toggle');
        Route::post('bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('admin.products.bulkDelete');
        Route::delete('{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
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
    });

    // comments

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    Route::post('/comments/{id}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    

    // BlogBlog
    Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
         Route::get('index', [BlogController::class, 'index'])->name('index');
        Route::get('create', [BlogController::class, 'create'])->name('create');
        Route::post('store', [BlogController::class, 'store'])->name('store');
        Route::get('show/{blog}', [BlogController::class, 'show'])->name('show');
        Route::get('edit/{blog}', [BlogController::class, 'edit'])->name('edit');
        Route::put('update/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('destroy/{blog}', [BlogController::class, 'destroy'])->name('destroy');
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

    // Quản lý sản phẩm

    Route::post('/admin/categories/store-quick', [AdminCategoryController::class, 'storeQuick'])->name('admin.categories.storeQuick');
    Route::post('/admin/regions/store-quick', [AdminRegionController::class, 'storeQuick'])->name('admin.regions.storeQuick');

    Route::prefix('products')->name('admin.products.')->group(function () {
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
        