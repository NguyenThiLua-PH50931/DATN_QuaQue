<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Trang danh sách sản phẩm (sơ lược)
    public function index()
    {
        // Lấy danh sách sản phẩm, gắn category và variant đầu tiên (active)
        $products = Product::with([
            'category',
            'region',
            'variants' => function ($q) {
                $q->where('active', 1)->orderBy('id');
            }
        ])->orderByDesc('id')->paginate(10);

        return view('backend.products.index', compact('products'));
    }

    // Trang chi tiết sản phẩm theo slug
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'variants', 'reviews.user'])
            ->firstOrFail();

        return view('backend.products.show', compact('product'));
    }
    public function create()
    {
        // Giả sử chỉ trả về view trống
        return view('backend.products.create');
    }
    public function toggleVariantStatus($id)
    {
        $variant = Variant::findOrFail($id);
        $variant->active = $variant->active ? 0 : 1;
        $variant->save();

        return back()->with('success', 'Đã cập nhật trạng thái biến thể!');
    }
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->active = !$product->active;
        $product->save();

        return back()->with('success', 'Đã cập nhật trạng thái sản phẩm!');
    }
    public function bulkDelete(Request $request)
    {
        Product::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Đã xóa các sản phẩm đã chọn!');
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete(); // Nếu muốn soft delete thì thêm SoftDeletes ở model Product
        return back()->with('success', 'Đã xóa sản phẩm "' . $product->name . '"!');
    }
}
