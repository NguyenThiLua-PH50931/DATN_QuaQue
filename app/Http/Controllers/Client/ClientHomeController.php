<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientHomeController extends Controller
{
    public function home()
    {
        $now = Carbon::now();

        // Lấy banner chính
        $mainHeroBanner = Banner::where('location', 'main_hero_banner')
            ->where('active', true)
            ->where('display_at', '<=', $now)
            ->where('display_end_at', '>=', $now)
            ->first();

        // Lấy banner quảng cáo nhỏ phía trên
        $smallPromoTopBanner = Banner::where('location', 'small_promo_banner_top')
            ->where('active', true)
            ->where('display_at', '<=', $now)
            ->where('display_end_at', '>=', $now)
            ->first();

        // Lấy banner quảng cáo nhỏ phía dưới
        $smallPromoBottomBanner = Banner::where('location', 'small_promo_banner_bottom')
            ->where('active', true)
            ->where('display_at', '<=', $now)
            ->where('display_end_at', '>=', $now)
            ->first();

        // Lấy banner slider (tối đa 4 banner)
        $sliderBanners = Banner::where('location', 'slider_banner')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->orderBy('created_at', 'asc')
            ->take(4)
            ->get();

        $topViewedProducts = Product::with('category')
            ->orderBy('view_total', 'desc')
            ->take(8)
            ->get();

        $latestProducts = Product::with('category')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();

        // Lấy các banner khác theo vị trí cụ thể
        $productSectionPromoLeftTop = Banner::where('location', 'product_section_promo_left_top')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $productSectionPromoLeftBottom = Banner::where('location', 'product_section_promo_left_bottom')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $newProductsCashbackBanner = Banner::where('location', 'new_products_cashback_banner')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $newProductsPromoLeft = Banner::where('location', 'new_products_promo_left')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $newProductsPromoRight = Banner::where('location', 'new_products_promo_right')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $lastPagePromoBanner = Banner::where('location', 'last_page_promo_banner')
            ->where('active', true)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->where('display_end_at', '>=', now());
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', now())
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        return view('frontend.home', compact(
            'mainHeroBanner',
            'smallPromoTopBanner',
            'smallPromoBottomBanner',
            'sliderBanners',
            'categories',
            'topViewedProducts',
            'latestProducts',
            'productSectionPromoLeftTop',
            'productSectionPromoLeftBottom',
            'newProductsCashbackBanner',
            'newProductsPromoLeft',
            'newProductsPromoRight',
            'lastPagePromoBanner'
        ));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $product->increment('view_total');
        $product->increment('view_day');
        $product->increment('view_week');
        $product->increment('view_month');

        return view('frontend.products.detail', compact('product'));
    }
}

