<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;

// ==================
// CLIENT ROUTES
// ==================
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

// Đăng ký người dùng
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Các trang client tĩnh
Route::view('/wishlist', 'frontend.wishlist.wishlist')->name('wishlist');
Route::view('/compare', 'frontend.pages.compare')->name('compare');
Route::view('/contact', 'frontend.pages.contact');
Route::view('/cart', 'frontend.cart.cart');
Route::view('/checkout', 'frontend.checkout.checkout');
Route::view('/products/category', 'frontend.products.category');
Route::view('/seller/become-seller', 'frontend.seller.become-seller');
Route::view('/seller/seller-dashboard', 'frontend.seller.seller-dashboard');

// ==================
// ADMIN ROUTES
// ==================
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::view('/', 'backend.dashboard')->name('dashboard');

    // Sản phẩm
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');

    // Danh mục
    Route::view('/categories', 'backend.categories.index')->name('categories.index');
    Route::view('/categories/create', 'backend.categories.create')->name('categories.create');

    // Thuộc tính sản phẩm
    Route::view('/attributes', 'backend.attributes.index')->name('attributes.index');
    Route::view('/attributes/create', 'backend.attributes.create')->name('attributes.create');

    // Người dùng
    Route::view('/users', 'backend.users.index')->name('users.index');
    Route::view('/users/create', 'backend.users.create')->name('users.create');

    // Vai trò
    Route::view('/roles', 'backend.roles.index')->name('roles.index');
    Route::view('/roles/create', 'backend.roles.create')->name('roles.create');

    // Media
    Route::view('/media', 'backend.media.index')->name('media.index');

    // 🚀 **Quản lý đơn hàng**
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
    
    // ✅ **Sửa lỗi tuyến đường xóa đơn hàng**
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Cập nhật trạng thái đơn hàng
    Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Phiếu giảm giá
    Route::view('/coupons', 'backend.coupons.index')->name('coupons.index');
    Route::view('/coupons/create', 'backend.coupons.create')->name('coupons.create');

    // Thuế
    Route::view('/taxes', 'backend.taxes.index')->name('taxes.index');

    // Đánh giá sản phẩm
    Route::view('/product-review', 'backend.product-review.index')->name('product-review.index');

    // Yêu cầu hỗ trợ
    Route::view('/support-ticket', 'backend.support-ticket.index')->name('support-ticket.index');

    // Cài đặt hồ sơ
    Route::view('/profile-setting', 'backend.profile-setting.index')->name('profile-setting.index');

    // Báo cáo
    Route::view('/reports', 'backend.reports.index')->name('reports.index');

    // Trang danh sách
    Route::view('/list-page', 'backend.list-page.index')->name('list-page.index');
});
