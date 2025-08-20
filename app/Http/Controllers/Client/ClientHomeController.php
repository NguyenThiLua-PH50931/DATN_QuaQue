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

        // Lấy blog mới nhất
        $blogs = Blog::latest()->take(6)->get();

        // Các banner chính
        $mainHeroBanner = Banner::where('location', 'main_hero_banner')
            ->where('active', true)
            ->where('display_at', '<=', $now)
            ->where('display_end_at', '>=', $now)
            ->first();

        $smallPromoTopBanner = Banner::where('location', 'small_promo_banner_top')
            ->where('active', true)
            ->where('display_at', '<=', $now)
            ->where('display_end_at', '>=', $now)
            ->first();

        $smallPromoBottomBanner = Banner::where('location', 'small_promo_banner_bottom')
            ->where('active', true)
            ->where('display_at', '<=', $now)
            ->where('display_end_at', '>=', $now)
            ->first();

        // Banner slider (tối đa 4 banner)
        $sliderBanners = Banner::where('location', 'slider_banner')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->orderBy('created_at', 'asc')
            ->take(4)
            ->get();

        // ---------------------- SẢN PHẨM NỔI BẬT ----------------------
        $topViewedProducts = Product::with(['categories', 'variants'])
            ->withCount(['reviews as avg_rating' => function ($q) {
                $q->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->where('active', 1)
            ->orderBy('view_total', 'desc')
            ->take(20)
            ->get();

        // Lọc sản phẩm còn hàng
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
        if ($availableTopProducts->count() < 8) {
            $additionalTopProducts = Product::with(['categories', 'variants'])
                ->withCount(['reviews as avg_rating' => function ($q) {
                    $q->select(DB::raw('coalesce(avg(rating),0)'));
                }])
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

        // ---------------------- SẢN PHẨM MỚI NHẤT ----------------------
        $latestProducts = Product::with(['categories', 'variants'])
            ->withCount(['reviews as avg_rating' => function ($q) {
                $q->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->where('active', 1)
            ->latest()
            ->take(20)
            ->get();

        $availableLatestProducts = collect();
        foreach ($latestProducts as $product) {
            if ($availableLatestProducts->count() >= 12) break;
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
        if ($availableLatestProducts->count() < 12) {
            $additionalLatestProducts = Product::with(['categories', 'variants'])
                ->withCount(['reviews as avg_rating' => function ($q) {
                    $q->select(DB::raw('coalesce(avg(rating),0)'));
                }])
                ->where('active', 1)
                ->whereNotIn('id', $availableLatestProducts->pluck('id'))
                ->latest()
                ->take(20)
                ->get();

            foreach ($additionalLatestProducts as $product) {
                if ($availableLatestProducts->count() >= 12) break;
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
        $latestProducts = $availableLatestProducts->take(12);

        // ---------------------- CATEGORIES ----------------------
        $categories = Category::all();

        // ---------------------- BANNER PROMO SECTION ----------------------
        $productSectionPromoLeftTop = Banner::where('location', 'product_section_promo_left_top')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $productSectionPromoLeftBottom = Banner::where('location', 'product_section_promo_left_bottom')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $newProductsCashbackBanner = Banner::where('location', 'new_products_cashback_banner')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $newProductsPromoLeft = Banner::where('location', 'new_products_promo_left')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $newProductsPromoRight = Banner::where('location', 'new_products_promo_right')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        $lastPagePromoBanner = Banner::where('location', 'last_page_promo_banner')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where('display_end_at', '>=', $now);
                })->orWhere(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->whereNull('display_end_at');
                });
            })
            ->first();

        // ---------------------- BEST SELLING PRODUCTS ----------------------
        $bestSellingProducts = Product::with(['categories', 'variants'])
            ->withCount(['reviews as avg_rating' => function ($q) {
                $q->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->where('products.active', 1)
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(12)
            ->get();

        // ---------------------- ALL PRODUCTS WITH AVG RATING (cho danh mục, filter...) ----------------------
        $products = Product::withCount('reviews')
            ->withCount(['reviews as avg_rating' => function ($q) {
                $q->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->get();

        // Trả về view
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
            'bestSellingProducts',
            'products'
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
