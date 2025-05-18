<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.backend');
});
Route::get('/admin', function () {
    return view('backend.dashboard');
});
// product
Route::get('/admin/products', function () {
    return view('backend.products.index');
});

Route::get('/admin/products/create', function () {
    return view('backend.products.create');
});


// cate
Route::get('/admin/categories', function () {
    return view('backend.categories.index');
});
Route::get('/admin/categories/create', function () {
    return view('backend.categories.create');
});
// Attributes
Route::get('/admin/attributes', function () {
    return view('backend.attributes.index');
});
Route::get('/admin/attributes/create', function () {
    return view('backend.attributes.create');
});
// user
Route::get('/admin/users', function () {
    return view('backend.users.index');
});
Route::get('/admin/users/create', function () {
    return view('backend.users.create');
});
// roles
Route::get('/admin/roles', function () {
    return view('backend.roles.index');
});
Route::get('/admin/roles/create', function () {
    return view('backend.roles.create');
});

// media
Route::get('/admin/media', function () {
    return view('backend.media.index');
});
// order
Route::get('/admin/orders', function () {
    return view('backend.orders.index');
});
Route::get('/admin/orders/detail', function () {
    return view('backend.orders.show');
});
Route::get('/admin/orders/tracking', function () {
    return view('backend.orders.tracking');
});
// coupons
Route::get('/admin/coupons', function () {
    return view('backend.coupons.index');
});
Route::get('/admin/coupons/create', function () {
    return view('backend.coupons.create');
});
// tax
Route::get('/admin/taxes', function () {
    return view('backend.taxes.index');
});
// product-review
Route::get('/admin/product-review', function () {
    return view('backend.product-review.index');
});
// support-ticket
Route::get('/admin/support-ticket', function () {
    return view('backend.support-ticket.index');
});
// profile-setting
Route::get('/admin/profile-setting', function () {
    return view('backend.profile-setting.index');
});
// reports
Route::get('/admin/reports', function () {
    return view('backend.reports.index');
});
// list-page
Route::get('/admin/list-page', function () {
    return view('backend.list-page.index');
});
