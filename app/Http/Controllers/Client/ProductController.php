<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Comment;
use Illuminate\Http\Request;
use App\Models\Client\Product;
use App\Models\Client\ProductVariant;
use Carbon\Carbon;
use App\Models\Admin\Banner;
use App\Models\Admin\Product as AdminProduct;
use App\Models\Client\CommentReply;
use App\Models\Client\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSearch;

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
        $priceMin = $request->filled('price_min') ? (int)$request->price_min : 0;
        $priceMax = $request->filled('price_max') ? (int)$request->price_max : 10000000;

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereHas('variants', function ($q) use ($priceMin, $priceMax) {
                $q->whereBetween('price', [$priceMin, $priceMax]);
            });
        } elseif ($request->filled('price_min')) {
            $query->whereHas('variants', function ($q) use ($priceMin) {
                $q->where('price', '>=', $priceMin);
            });
        } elseif ($request->filled('price_max')) {
            $query->whereHas('variants', function ($q) use ($priceMax) {
                $q->where('price', '<=', $priceMax);
            });
        }
        // Filter theo rating trung bình
        if ($request->filled('rating')) {
            $query->withAvg('reviews', 'rating')
                ->havingRaw('ROUND(reviews_avg_rating) = ?', [$request->rating]);
        }
        // Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderByRaw('(
                        SELECT MIN(price) FROM product_variants
                        WHERE product_id = products.id AND stock > 0 AND active = 1
                    ) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('(
                        SELECT MIN(price) FROM product_variants
                        WHERE product_id = products.id AND stock > 0 AND active = 1
                    ) DESC');
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
            // Mặc định: sắp xếp theo stock (có hàng trước, hết hàng sau)
            $query->orderByRaw('
                CASE
                    WHEN products.has_variants = 1 THEN
                        (SELECT COUNT(*) FROM product_variants WHERE product_id = products.id AND stock > 0 AND active = 1)
                    ELSE
                        0
                END DESC
            ')->orderBy('id', 'desc');
        }
        // Lấy tất cả sản phẩm (không phân trang ở đây)
        $allProducts = $query->get();

        // Phân loại sản phẩm còn hàng và hết hàng
        $inStockProducts = collect();
        $outOfStockProducts = collect();
        foreach ($allProducts as $product) {
            if ($product->has_variants) {
                // Có biến thể
                $hasInStockVariant = $product->variants->where('stock', '>', 0)->where('active', 1)->count() > 0;
                if ($hasInStockVariant) {
                    $inStockProducts->push($product);
                } else {
                    $outOfStockProducts->push($product);
                }
            } else {
                // Không có biến thể
                $defaultVariant = $product->variants->first();
                if ($defaultVariant && $defaultVariant->stock > 0) {
                    $inStockProducts->push($product);
                } else {
                    $outOfStockProducts->push($product);
                }
            }
        }
        // Gộp lại: còn hàng trước, hết hàng sau
        $finalProducts = $inStockProducts->concat($outOfStockProducts);

        // Phân trang thủ công
        $perPage = 12;
        $currentPage = $request->input('page', 1);
        $pagedProducts = $finalProducts->forPage($currentPage, $perPage);
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedProducts,
            $finalProducts->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Lấy danh sách danh mục và vùng miền cho filter
        $categories = \App\Models\admin\Category::all();
        $regions = \App\Models\admin\Region::all();

        return view('frontend.products.index', compact('products', 'categories', 'regions', 'priceMin', 'priceMax'));
    }
    /**
     * Hiển thị chi tiết sản phẩm.
     */
public function show($slug, Request $request)
    {
        $product = Product::with([
            'images',
            'category',
            'region',
            'variants.attributeValues.attribute',
            'reviews.user',
            'reviews.productVariantId',
            'comments.user',
            'comments.replies.user'
        ])
            ->where('slug', $slug)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $this->increaseView($product);
            ProductSearch::create([
        'user_id'    => auth()->id(),
        'product_id' => $product->id,
        'keyword'    => $request->input('q'), // truyền từ url nếu có, không thì null cũng được
    ]);

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
        $reviews = $product->reviews()
            ->with(['user', 'productVariantId'])
            ->orderByDesc('created_at')
            ->get();
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0
            ? round($reviews->avg('rating'), 2)
            : 0;

        // Đếm số lượng review mỗi mức sao
        $ratingsCount = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingsCount[$i] = $reviews->where('rating', $i)->count();
        }
        $user = Auth::user();
        $currentUser = Auth::user();
        // LẤY BÌNH LUẬN KÈM TRẢ LỜI CHO SẢN PHẨM
        $comments = $product->comments()
            ->with(['user', 'replies.user']) // giả sử replies user là người trả lời
            ->where('status', 'visible')
            ->latest()
            ->get();
        $starOptions = $reviews->pluck('rating')->unique()->sortDesc()->values();
        // Lọc các phân loại đang có review
        $variantOptions = $reviews->pluck('product_variant_value_id')->unique()->values();
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
            'comments'                => $comments,    // thêm biến comments vào view
            'currentUser'             => $currentUser,
            'averageRating'           => $averageRating,
            'totalReviews'            => $totalReviews,
            'ratingsCount'            => $ratingsCount,
            'starOptions' => $starOptions,
                'variantOptions' => $variantOptions,
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

        // Lấy ảnh mô tả từ bảng product_images
        $descriptionImages = $product->images->map(function ($image) {
            return asset('storage/' . $image->image_url);
        })->toArray();

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'origin' => $product->origin,
                'category_name' => $product->category->name ?? '',
                'image' => $product->image ? asset('storage/' . $product->image) : null, // Ảnh product
                'description_images' => $descriptionImages, // Ảnh mô tả
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
            'product_variant_id' => 'required|exists:product_variants,id',
            'comment' => 'required|string',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        // Kiểm tra xem user đã mua biến thể này và đơn hàng đã được giao chưa
        $hasDeliveredVariant = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_variant_id', $request->product_variant_id)
            ->where('orders.user_id', $user->id)
            ->where('orders.status', 'delivered') // status 'delivered' là đã giao
            ->exists();

        if (!$hasDeliveredVariant) {
            return redirect()->back()->withErrors(['Bạn chỉ có thể đánh giá sản phẩm sau khi đã nhận hàng.']);
        }

        // Kiểm tra đã đánh giá chưa
        $hasReviewed = Review::where('user_id', $user->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->back()->withErrors(['Bạn chỉ được đánh giá sản phẩm này một lần.']);
        }

        // Lưu đánh giá
        Review::create([
            'product_id'          => DB::table('product_variants')->where('id', $request->product_variant_id)->value('product_id'),
            'product_variant_id'  => $request->product_variant_id,
            'user_id'             => $user->id,
            'rating'              => $request->rating,
            'comment'             => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm. Lưu ý: Bạn chỉ được đánh giá 1 lần cho mỗi sản phẩm.');
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

    // AJAX search cho header
    public function searchAjax(Request $request)
    {
        $query = $request->input('q');
        $products = Product::where('active', 1)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhereHas('category', function ($cat) use ($query) {
                        $cat->where('name', 'like', "%$query%");
                    })
                    ->orWhereHas('region', function ($reg) use ($query) {
                        $reg->where('name', 'like', "%$query%");
                    });
            })
            ->select('id', 'name', 'slug', 'image')
            ->limit(10)
            ->get();
        return response()->json($products);
    }
    public function comment(Request $request, $productId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->product_id = $productId;
        $comment->content = $request->content;
        $comment->status = 'visible';
        $comment->save();

        $comment->load('user');

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận đã được gửi',
            'comment' => $comment,
        ]);
    }

    // Tạo trả lời cho comment
    public function commentReply(Request $request, $commentId)
    {
        $request->validate([
            'reply' => 'required|string|max:500',
        ]);

        $reply = new CommentReply();
        $reply->comment_id = $commentId;
        $reply->user_id = Auth::id();
        $reply->reply = $request->reply;
        $reply->save();

        $reply->load('user');

        return response()->json([
            'status' => 'success',
            'message' => 'Trả lời đã được gửi',
            'reply' => $reply,
        ]);
    }

    // Cập nhật bình luận
    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = Comment::findOrFail($commentId);
        $user = Auth::user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền sửa bình luận này'], 403);
        }

        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật bình luận thành công',
            'comment' => $comment,
        ]);
    }

    // Xóa bình luận
    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $user = Auth::user();

        if ($user->id !== $comment->user_id && $user->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền xóa bình luận này'], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa bình luận thành công',
        ]);
    }
    public function updateReply(Request $request, $replyId)
    {
        $request->validate([
            'reply' => 'required|string|max:500',
        ]);

        $reply = CommentReply::findOrFail($replyId);

        if (Auth::id() !== $reply->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền sửa trả lời này'], 403);
        }

        $reply->reply = $request->reply;
        $reply->save();

        return response()->json([
            'status' => 'success',
            'reply' => $reply,
        ]);
    }

    public function deleteReply($replyId)
    {
        $reply = CommentReply::findOrFail($replyId);

        $user = Auth::user();
        if ($user->id !== $reply->user_id && $user->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền xóa trả lời này'], 403);
        }

        $reply->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa trả lời thành công',
        ]);
    }
}
