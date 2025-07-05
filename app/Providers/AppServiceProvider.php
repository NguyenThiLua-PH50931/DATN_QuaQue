<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Client\CartItem;

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
         View::composer('*', function ($view) {
        $userId = Auth::id();
        $cartItems = [];

        if ($userId) {
            $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        }

        $view->with('cartItems', $cartItems);
    });
    }
}
