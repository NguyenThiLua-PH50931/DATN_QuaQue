<?php

use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

// Client Routes
Route::get('/', function () {
    return view('frontend.home');
})->name('home');

// Đăng ký
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Đăng nhập
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'checklogin'])->name('checklogin');

// Sản phẩm yêu thích
Route::get('/wishlist', function () {
    return view('frontend.wishlist.wishlist');
})->name('wishlist');

// Liên hệ
Route::get('/contact', function () {
    return view('frontend.pages.contact');
})->name('contact');

// Giỏ hàng
Route::get('/cart', function () {
    return view('frontend.cart.cart');
})->name('cart');

// Thanh toán
Route::get('/checkout', function () {
    return view('frontend.checkout.checkout');
})->name('checkout');

// Sản phẩm theo danh mục
Route::get('/products/category', function () {
    return view('frontend.products.category');
})->name('products.category');

// Seller Routes
Route::get('/seller/become-seller', function () {
    return view('frontend.seller.become-seller');
})->name('seller.become-seller');

Route::get('/seller/seller-dashboard', function () {
    return view('frontend.seller.seller-dashboard');
})->name('seller.dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('backend.dashboard');
    })->name('dashboard');

    // Products Management (Admin)
    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}', [AdminProductController::class, 'show'])->name('show'); // Assuming show uses slug
        Route::post('/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/variants/{id}/toggle-status', [AdminProductController::class, 'toggleVariantStatus'])->name('variants.toggle-status'); // Assuming variant toggle exists
        Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulkDelete'); // Corrected name
    });

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::delete('/categories/{id}/trashed', [CategoryController::class, 'softDelete'])->name('categories.softDelete');
    Route::delete('/categories/{id}/force', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::get('/categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');

    // Regions
    Route::get('/regions', [RegionController::class, 'index'])->name('regions.index');
    Route::post('/regions', [RegionController::class, 'store'])->name('regions.store');
    Route::put('/regions/{id}', [RegionController::class, 'update'])->name('regions.update');
    Route::get('/regions/create', [RegionController::class, 'create'])->name('regions.create');
    Route::get('/regions/{id}/edit', [RegionController::class, 'edit'])->name('regions.edit');
    Route::delete('/regions/{id}/soft', [RegionController::class, 'softDelete'])->name('regions.softDelete');
    Route::delete('/regions/{id}/force', [RegionController::class, 'forceDelete'])->name('regions.forceDelete');
    Route::post('/regions/{id}/restore', [RegionController::class, 'restore'])->name('regions.restore');
    Route::get('/regions/trashed', [RegionController::class, 'trashed'])->name('regions.trashed');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // Comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CommentController::class, 'update'])->name('update');
        Route::post('/{id}/reply', [CommentController::class, 'reply'])->name('reply');
    });

    // Attributes
    Route::get('/attributes', function () {
        return view('backend.attributes.index');
    })->name('attributes.index');
    Route::get('/attributes/create', function () {
        return view('backend.attributes.create');
    })->name('attributes.create');

    // Users
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/hidden', [UserController::class, 'hidden'])->name('hidden');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('delete');
    });

    // Roles
    Route::get('/roles', function () {
        return view('backend.roles.index');
    })->name('roles.index');
    Route::get('/roles/create', function () {
        return view('backend.roles.create');
    })->name('roles.create');

    // Media
    Route::get('/media', function () {
        return view('backend.media.index');
    })->name('media.index');

    // Orders
    Route::get('/orders', function () {
        return view('backend.orders.index');
    })->name('orders.index');
    Route::get('/orders/detail', function () {
        return view('backend.orders.show');
    })->name('orders.show');
    Route::get('/orders/tracking', function () {
        return view('backend.orders.tracking');
    })->name('orders.tracking');

    // Coupons
    Route::get('/coupons', function () {
        return view('backend.coupons.index');
    })->name('coupons.index');
    Route::get('/coupons/create', function () {
        return view('backend.coupons.create');
    })->name('coupons.create');

    // Taxes
    Route::get('/taxes', function () {
        return view('backend.taxes.index');
    })->name('taxes.index');

    // Product Review
    Route::get('/product-review', function () {
        return view('backend.product-review.index');
    })->name('product-review.index');

    // Support Ticket
    Route::get('/support-ticket', function () {
        return view('backend.support-ticket.index');
    })->name('support-ticket.index');

    // Profile Setting
    Route::get('/profile-setting', function () {
        return view('backend.profile-setting.index');
    })->name('profile-setting.index');

    // Reports
    Route::get('/reports', function () {
        return view('backend.reports.index');
    })->name('reports.index');

    // List Page
    Route::get('/list-page', function () {
        return view('backend.list-page.index');
    })->name('list-page.index');
});
