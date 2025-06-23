<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\Product;
use App\Models\Client\ProductVariant;
use Carbon\Carbon;

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
        $product = Product::with([
            'images',
            'category',
            'region',
            'variants.attributeValues.attribute', // Load đủ để group attributes/values cho view
            'reviews.user',
            'comments.user',
            'comments.replies.admin'
        ])
            ->where('slug', $slug)
            ->where('active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

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
                'value_ids' => $v->attributeValues->pluck('id')->sort()->values()->all(),
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

        return view('frontend.products.detail', [
            'product'    => $product,
            'variants'   => $variants,
            'attributes' => $attributeOptions,
            'variantMap' => $variantMap,
            'related'    => $related,
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
}
