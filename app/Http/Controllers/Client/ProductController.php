<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\Product;
use App\Models\Client\ProductVariant;
use Carbon\Carbon;
use App\Models\Admin\Banner;
use App\Models\Admin\Product as AdminProduct;
use App\Models\Client\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // all products
    public function index(Request $request)
    {
        $query = Product::with(['category', 'region', 'images', 'variants', 'reviews'])
            ->where('active', 1);

        // Filter theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        // Filter theo vùng miền
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        // Filter theo giá
        if ($request->filled('price_min')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->price_min);
            });
        }
        if ($request->filled('price_max')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->price_max);
            });
        }
        // Filter theo rating trung bình
        if ($request->filled('rating')) {
            $query->whereHas('reviews', function ($q) use ($request) {
                $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }
        // Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('(SELECT MAX(price) FROM product_variants WHERE product_id = products.id) DESC');
                    break;
                case 'rating':
                    $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        // Phân trang
        $products = $query->paginate(12)->appends($request->query());

        // Lấy danh sách danh mục và vùng miền cho filter
        $categories = \App\Models\admin\Category::all();
        $regions = \App\Models\admin\Region::all();

        return view('frontend.products.index', compact('products', 'categories', 'regions'));
    }
    /**
     * Hiển thị chi tiết sản phẩm.
     */
    public function show($slug)
    {
        $product = Product::with([
            'images',
            'category',
            'region',
            'variants.attributeValues.attribute',
            'reviews.user',
            'comments.user',
            'comments.replies.admin'
        ])
            ->where('slug', $slug)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $this->increaseView($product);

        $variants = $product->variants()->where('active', 1)
            ->with('attributeValues.attribute')->get();

        // Group các thuộc tính (attribute) + value
        $attributeOptions = [];
        foreach ($variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attrId = $value->attribute->id;
                $attrName = $value->attribute->name;
                if (!isset($attributeOptions[$attrId])) {
                    $attributeOptions[$attrId] = [
                        'name' => $attrName,
                        'values' => []
                    ];
                }
                $attributeOptions[$attrId]['values'][$value->id] = $value->value;
            }
        }

        // Mapping variant (dùng cho JS, AJAX tìm variant theo tổ hợp value id)
        $variantMap = $variants->map(function ($v) {
            return [
                'id' => $v->id,
                'sku' => $v->sku,
                'stock' => $v->stock,
                'price' => $v->price,
                'image' => $v->image ? asset('storage/' . $v->image) : null,
                // Ép kiểu int cho các value_ids và sort
                'value_ids' => $v->attributeValues->pluck('id')->map(fn($id) => (int)$id)->sort()->values()->all(),
                'active' => (int) $v->active,
            ];
        });

        // Lấy sản phẩm cùng danh mục, active = 1, không lấy sp đang xem
        $relatedProducts = Product::with(['images', 'variants' => function ($query) {
            $query->where('active', 1);
        }])
            ->where('category_id', $product->category_id)
            ->where('active', 1)
            ->where('id', '!=', $product->id)
            ->whereNull('deleted_at')
            ->limit(15)
            ->get();

        // Nếu không có sản phẩm cùng danh mục thì lấy 15 sp random active, cũng eager load variants active = 1
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::with(['images', 'variants' => function ($query) {
                $query->where('active', 1);
            }])
                ->where('active', 1)
                ->where('id', '!=', $product->id)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->limit(15)
                ->get();
        }

        $relatedTitle = $relatedProducts->isEmpty()
            ? 'Các sản phẩm nổi bật khác'
            : 'Sản phẩm cùng danh mục';

        // Lấy sản phẩm thịnh hành (topViewedProducts)
        $topViewedProducts = AdminProduct::with('category')
            ->where('active', 1)
            ->orderBy('view_total', 'desc')
            ->limit(8)
            ->get();

        // Lấy banner dọc (product_section_promo_left_top)
        $now = now();
        $productSectionPromoLeftTop = Banner::where('location', 'product_section_promo_left_top')
            ->where('active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->where('display_at', '<=', $now)
                        ->where(function ($q2) use ($now) {
                            $q2->where('display_end_at', '>=', $now)
                                ->orWhereNull('display_end_at');
                        });
                });
            })
            ->first();

        // top sp thang sidebar
        $topMonthlyProducts = Product::where('active', 1)
            ->where('id', '!=', $product->id)
            ->orderByDesc('view_month')
            ->limit(4)
            ->get();
        $reviews = $product->reviews()->with('user')->latest()->get();

        return view('frontend.products.detail', [
            'product'                 => $product,
            'variants'                => $variants,
            'attributes'              => $attributeOptions,
            'variantMap'              => $variantMap,
            'relatedProducts'         => $relatedProducts,
            'relatedTitle'            => $relatedTitle,
            'topMonthlyProducts'      => $topMonthlyProducts,
            'productSectionPromoLeftTop' => $productSectionPromoLeftTop,
            'reviews'    => $reviews,
            'topViewedProducts'       => $topViewedProducts,
        ]);
    }
    public function quickView($slug)
    {
        $product = Product::with([
            'images',
            'category',
            'variants.attributeValues.attribute',
            'reviews'
        ])
            ->where('slug', $slug)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $variants = $product->variants->where('active', 1);

        // Group các thuộc tính (attribute) + value
        $attributeOptions = [];
        foreach ($variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attrId = $value->attribute->id;
                $attrName = $value->attribute->name;
                if (!isset($attributeOptions[$attrId])) {
                    $attributeOptions[$attrId] = [
                        'name' => $attrName,
                        'values' => []
                    ];
                }
                $attributeOptions[$attrId]['values'][$value->id] = $value->value;
            }
        }

        // Mapping variant (dùng cho JS, AJAX tìm variant theo tổ hợp value id)
        $variantMap = $variants->map(function ($v) {
            return [
                'id' => $v->id,
                'sku' => $v->sku,
                'stock' => $v->stock,
                'price' => $v->price,
                'image' => $v->image ? asset('storage/' . $v->image) : null,
                'value_ids' => $v->attributeValues->pluck('id')->map(fn($id) => (int)$id)->sort()->values()->all(),
                'active' => (int) $v->active,
            ];
        })->values();

        $avgRating = round($product->reviews->avg('rating') ?? 0);

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category_name' => $product->category->name ?? '',
                'image' => $product->image ? asset('storage/' . $product->image) : null, // Ảnh product
                'variants' => $variantMap, // Mảng variant
                'attributes' => $attributeOptions,
                'avg_rating' => $avgRating,
                'review_count' => $product->reviews->count(),
            ]
        ]);
    }


    /**
     * Hàm tăng view ngày/tuần/tháng/tổng
     */
    protected function increaseView(Product $product)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Nếu ngày đã đổi so với lần cập nhật trước, reset view_day
        if ($product->updated_at && $product->updated_at->format('Y-m-d') !== $now->format('Y-m-d')) {
            $product->view_day = 0;
        }

        // Nếu tuần đã đổi (theo ISO-8601) so với lần cập nhật trước, reset view_week
        if ($product->updated_at && $product->updated_at->format('oW') !== $now->format('oW')) {
            $product->view_week = 0;
        }

        // Nếu tháng đã đổi so với lần cập nhật trước, reset view_month
        if ($product->updated_at && $product->updated_at->format('Y-m') !== $now->format('Y-m')) {
            $product->view_month = 0;
        }

        // Tăng lượt xem
        $product->view_total += 1;
        $product->view_day += 1;
        $product->view_week += 1;
        $product->view_month += 1;

        $product->save();
    }

    /**
     * Lấy các option thuộc tính từ các variant
     */
    protected function getAttributeOptions($variants)
    {
        $options = [];
        foreach ($variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attrId = $value->attribute->id;
                $attrName = $value->attribute->name;
                if (!isset($options[$attrId])) {
                    $options[$attrId] = [
                        'name' => $attrName,
                        'values' => []
                    ];
                }
                $options[$attrId]['values'][$value->id] = $value->value;
            }
        }
        return $options;
    }

    /**
     * (Optional) Lấy giá, stock theo biến thể (cho ajax nếu bạn muốn)
     */
    public function getVariant(Request $request)
    {
        $variantId = $request->input('variant_id');
        $variant = ProductVariant::with('attributeValues.attribute')->findOrFail($variantId);

        return response()->json([
            'price'      => $variant->price,
            'stock'      => $variant->stock,
            'image'      => $variant->image ? asset($variant->image) : null,
            'attributes' => $variant->attributeValues->map(function ($v) {
                return [
                    'attribute' => $v->attribute->name,
                    'value'     => $v->value,
                ];
            })
        ]);
    }
    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'comment'    => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        Review::create([
            'product_id' => $request->product_id,
            'user_id'    => $user?->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi.');
    }
    public function filterReviews(Request $request, $slug)
    {
        $ratingFilter = $request->query('star');

        $product = Product::where('slug', $slug)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $reviews = $product->reviews()
            ->with('user')
            ->when($ratingFilter, fn($q) => $q->where('rating', $ratingFilter))
            ->latest()
            ->get();

        return view('frontend.products.review-items', compact('reviews'));
    }
}
