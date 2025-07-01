<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\Product;
use App\Models\Client\ProductVariant;
use Carbon\Carbon;
use App\Models\Admin\Banner;
use App\Models\Admin\Product as AdminProduct;

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
                'value_ids' => $v->attributeValues->pluck('id')->map(fn($id) => (int)$id)->sort()->values()->all(),6
            ];
        });


        // Lấy sản phẩm liên quan (option)
        $related = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->limit(8)
            ->get();

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
        return view('frontend.products.detail', [
            'product'    => $product,
            'variants'   => $variants,
            'attributes' => $attributeOptions,
            'variantMap' => $variantMap,
            'related'    => $related,
            'topMonthlyProducts' => $topMonthlyProducts,
            'productSectionPromoLeftTop' => $productSectionPromoLeftTop,
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
}
