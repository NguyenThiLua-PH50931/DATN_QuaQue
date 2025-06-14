<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\admin\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    // Danh sách biến thể của sản phẩm
    public function index($productId)
    {
        $product = Product::with('variants')->findOrFail($productId);
        return view('backend.products.variants.index', compact('product'));
    }

    // Form tạo biến thể mới
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('backend.products.variants.create', compact('product'));
    }

    // Lưu biến thể mới
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'active' => 'required|boolean',
        ], [
            'name.required' => 'Tên biến thể là bắt buộc.',
            'price.required' => 'Giá biến thể là bắt buộc.',
            'price.numeric' => 'Giá phải là số hợp lệ.',
            'stock.required' => 'Số lượng là bắt buộc.',
            'stock.integer' => 'Số lượng phải là số nguyên.',
        ]);

        $variant = new ProductVariant();
        $variant->product_id = $product->id;
        $variant->name = $request->name;
        $variant->description = $request->description;
        $variant->price = $request->price;
        $variant->stock = $request->stock;
        $variant->sku = $request->sku;
        $variant->barcode = $request->barcode;
        $variant->active = $request->active;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('variants', 'public');
            $variant->image = $path;
        }

        $variant->save();

        return redirect()->route('admin.products.variant.index', $product->id)->with('success', 'Tạo biến thể thành công!');
    }

    // Xem chi tiết biến thể
    public function show($id)
    {
        $variant = ProductVariant::findOrFail($id);
        return view('backend.products.variants.show', compact('variant'));
    }

    // Form chỉnh sửa biến thể
    public function edit($id)
    {
        $variant = ProductVariant::findOrFail($id);
        return view('backend.products.variants.edit', compact('variant'));
    }

    // Cập nhật biến thể
    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'active' => 'required|boolean',
        ], [
            'name.required' => 'Tên biến thể là bắt buộc.',
            'price.required' => 'Giá biến thể là bắt buộc.',
            'price.numeric' => 'Giá phải là số hợp lệ.',
            'stock.required' => 'Số lượng là bắt buộc.',
            'stock.integer' => 'Số lượng phải là số nguyên.',
        ]);

        $variant->name = $request->name;
        $variant->description = $request->description;
        $variant->price = $request->price;
        $variant->stock = $request->stock;
        $variant->sku = $request->sku;
        $variant->barcode = $request->barcode;
        $variant->active = $request->active;

        if ($request->hasFile('image')) {
            // Xóa file cũ nếu có (tùy bạn thêm)
            $path = $request->file('image')->store('variants', 'public');
            $variant->image = $path;
        }

        $variant->save();

        return redirect()->route('admin.products.variant.index', $variant->product_id)->with('success', 'Cập nhật biến thể thành công!');
    }

    // Xóa biến thể
    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $productId = $variant->product_id;
        $variant->delete();

        return redirect()->route('admin.products.variant.index', $productId)->with('success', 'Xóa biến thể thành công!');
    }

    // Xóa nhiều biến thể
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids; // mảng id các biến thể cần xóa

        if ($ids && is_array($ids)) {
            $variants = ProductVariant::whereIn('id', $ids)->get();
            $productId = null;
            foreach ($variants as $variant) {
                $productId = $variant->product_id;
                $variant->delete();
            }
            return redirect()->route('admin.products.variant.index', $productId)->with('success', 'Xóa nhiều biến thể thành công!');
        }

        return redirect()->back()->with('error', 'Không có biến thể nào được chọn để xóa.');
    }
}
