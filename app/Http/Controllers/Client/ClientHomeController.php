<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use App\Models\admin\Blog;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientHomeController extends Controller
{
    public function home()
    {
        $now = Carbon::now();
        $blogs = Blog::latest()->take(6)->get();

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

        // Sản phẩm nổi bật - lấy nhiều hơn để thay thế khi hết hàng
        $topViewedProducts = Product::with(['category', 'variants'])
            ->where('active', 1)
            ->orderBy('view_total', 'desc')
            ->take(20) // Lấy 20 sản phẩm để có thể thay thế
            ->get();

        // Lọc sản phẩm nổi bật có hàng, nếu thiếu thì lấy thêm
        $availableTopProducts = collect();
        foreach ($topViewedProducts as $product) {
            if ($availableTopProducts->count() >= 8) break;

            if ($product->has_variants) {
                $availableVariants = $product->variants->where('stock', '>', 0)->where('active', 1);
                if ($availableVariants->count() > 0) {
                    $availableTopProducts->push($product);
                }
            } else {
                if ($product->stock > 0) {
                    $availableTopProducts->push($product);
                }
            }
        }

        // Nếu chưa đủ 8 sản phẩm, lấy thêm từ danh sách có lượt view cao
        if ($availableTopProducts->count() < 8) {
            $additionalTopProducts = Product::with(['category', 'variants'])
                ->where('active', 1)
                ->whereNotIn('id', $availableTopProducts->pluck('id'))
                ->orderBy('view_total', 'desc')
                ->take(20)
                ->get();

            foreach ($additionalTopProducts as $product) {
                if ($availableTopProducts->count() >= 8) break;

                if ($product->has_variants) {
                    $availableVariants = $product->variants->where('stock', '>', 0)->where('active', 1);
                    if ($availableVariants->count() > 0) {
                        $availableTopProducts->push($product);
                    }
                } else {
                    if ($product->stock > 0) {
                        $availableTopProducts->push($product);
                    }
                }
            }
        }

        $topViewedProducts = $availableTopProducts->take(8);

        // Sản phẩm mới - lấy nhiều hơn để thay thế khi hết hàng
        $latestProducts = Product::with(['category', 'variants'])
            ->where('active', 1)
            ->latest()
            ->take(20) // Lấy 20 sản phẩm để có thể thay thế
            ->get();

        // Lọc sản phẩm mới có hàng, nếu thiếu thì lấy thêm
        $availableLatestProducts = collect();
        foreach ($latestProducts as $product) {
            if ($availableLatestProducts->count() >= 8) break;

            if ($product->has_variants) {
                $availableVariants = $product->variants->where('stock', '>', 0)->where('active', 1);
                if ($availableVariants->count() > 0) {
                    $availableLatestProducts->push($product);
                }
            } else {
                if ($product->stock > 0) {
                    $availableLatestProducts->push($product);
                }
            }
        }

        // Nếu chưa đủ 8 sản phẩm, lấy thêm từ danh sách sản phẩm mới
        if ($availableLatestProducts->count() < 8) {
            $additionalLatestProducts = Product::with(['category', 'variants'])
                ->where('active', 1)
                ->whereNotIn('id', $availableLatestProducts->pluck('id'))
                ->latest()
                ->take(20)
                ->get();

            foreach ($additionalLatestProducts as $product) {
                if ($availableLatestProducts->count() >= 8) break;

                if ($product->has_variants) {
                    $availableVariants = $product->variants->where('stock', '>', 0)->where('active', 1);
                    if ($availableVariants->count() > 0) {
                        $availableLatestProducts->push($product);
                    }
                } else {
                    if ($product->stock > 0) {
                        $availableLatestProducts->push($product);
                    }
                }
            }
        }

        $latestProducts = $availableLatestProducts->take(8);

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

            // Sản phẩm bán chạy: (lấy theo tổng số sản phẩm được bán ra)
        $bestSellingProducts = Product::with(['category', 'variants'])
            ->select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->where('products.active', 1)
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(12)
            ->get();

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
            'lastPagePromoBanner',
            'blogs',
            'bestSellingProducts'
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
