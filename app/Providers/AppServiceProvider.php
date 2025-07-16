<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Client\CartItem;
use Illuminate\Pagination\Paginator;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        View::composer(['frontend.header'], function ($view) {
    $userId = Auth::id();
    $globalCartItems = [];
    if ($userId) {
        $globalCartItems = CartItem::with('product')->where('user_id', $userId)->get();
    }
    $view->with('globalCartItems', $globalCartItems);
});

    }
}
