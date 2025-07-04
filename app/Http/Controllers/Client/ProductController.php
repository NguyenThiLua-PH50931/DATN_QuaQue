<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\Product;
use App\Models\Client\ProductVariant;
use App\Models\Client\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // all products
    public function index()
    {
        $products = Product::with(['category', 'region', 'images'])->where('active', 1)->get();
        return view('frontend.products.index', compact('products'));
    }
    /**
     * Hiển thị chi tiết sản phẩm.
     */
    public function show($slug)
    {
        // Lấy sản phẩm theo slug, load các liên kết liên quan
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

        // Tăng view count
        $this->increaseView($product);

        // Lấy các biến thể sản phẩm
        $variants = $product->variants()->where('active', 1)->get();

        // Lấy thông tin thuộc tính từ variants cho select (chọn biến thể)
        $attributes = $this->getAttributeOptions($variants);

        // Lấy sản phẩm liên quan (cùng category)
        $related = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->limit(8)
            ->get();
        $reviews = $product->reviews()->with('user')->latest()->get();
        return view('frontend.products.detail', [
            'product'    => $product,
            'variants'   => $variants,
            'attributes' => $attributes,
            'related'    => $related,
            'reviews'    => $reviews,
        ]);
    }

    /**
     * Hàm tăng view ngày/tuần/tháng/tổng
     */
    protected function increaseView($product)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Reset view_day nếu sang ngày mới
        if ($product->updated_at && $product->updated_at->format('Y-m-d') !== $now->format('Y-m-d')) {
            $product->view_day = 0;
        }
        // Reset view_week nếu sang tuần mới
        if ($product->updated_at && $product->updated_at->format('W') !== $now->format('W')) {
            $product->view_week = 0;
        }
        // Reset view_month nếu sang tháng mới
        if ($product->updated_at && $product->updated_at->format('m') !== $now->format('m')) {
            $product->view_month = 0;
        }

        $product->view_total += 1;
        $product->view_day   += 1;
        $product->view_week  += 1;
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

    // đánh giá sản phẩm
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
