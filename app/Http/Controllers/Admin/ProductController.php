<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Region;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Trang danh sách sản phẩm (sơ lược)
    public function index()
    {
        // Lấy danh sách sản phẩm, gắn category và variant đầu tiên (active)
        $products = Product::with([
            'category',
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
    public function create()
    {
        $categories = Category::all();
        $regions = Region::all();
        return view('backend.products.create', compact('categories', 'regions'));
    }

    public function store(Request $request)
    {
        // Custom messages cho tiếng Việt
        $messages = [
            'name.required' => 'Tên sản phẩm không được bỏ trống.',
            'category_id.required' => 'Bạn phải chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'region_id.required' => 'Bạn phải chọn vùng miền.',
            'region_id.exists' => 'Vùng miền không hợp lệ.',
            'image.required' => 'Ảnh đại diện là bắt buộc.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải là jpeg, png, jpg, gif hoặc webp.',
            'origin.required' => 'Bạn phải nhập xuất xứ.',
            'variants.required' => 'Phải có ít nhất 1 biến thể.',
            'variants.*.name.required' => 'Tên biến thể không được bỏ trống.',
            'variants.*.price.required' => 'Bạn phải nhập giá biến thể.',
            'variants.*.price.numeric' => 'Giá biến thể phải là số.',
            'variants.*.stock.required' => 'Bạn phải nhập tồn kho.',
            'variants.*.stock.integer' => 'Tồn kho phải là số nguyên.',
            'variants.*.sku.required' => 'SKU không được bỏ trống.',
        ];

        $request->validate([
            'name'        => 'required|max:200',
            'category_id' => 'required|exists:categories,id',
            'region_id'   => 'required|exists:regions,id',
            'description' => 'nullable',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'origin'      => 'required|max:100',
            'active'      => 'boolean',
            'variants'    => 'required|array|min:1',
            'variants.*.name' => 'required',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'required',
        ], $messages);

        DB::beginTransaction();
        try {
            // 1. Upload ảnh đại diện
            $imgPath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imgPath = $file->storeAs('products', $fileName, 'public');
            }

            // 2. Lưu sản phẩm
            $product = Product::create([
                'name'        => $request->name,
                'slug'        => Str::slug($request->name) . '-' . time(),
                'category_id' => $request->category_id,
                'region_id'   => $request->region_id,
                'description' => $request->description,
                'image'       => $imgPath,
                'origin'      => $request->origin,
                'active'      => $request->active ? 1 : 0,
            ]);

            // 3. Ảnh mô tả
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imgName = Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME))
                        . '-' . time() . rand(10, 99) . '.' . $img->getClientOriginalExtension();
                    $imgPathDetail = $img->storeAs('products', $imgName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => $imgPathDetail,
                    ]);
                }
            }

            // 4. Biến thể
            foreach ($request->variants as $variant) {
                Variant::create([
                    'product_id'  => $product->id,
                    'name'        => $variant['name'],
                    'description' => $variant['description'] ?? null,
                    'price'       => $variant['price'],
                    'stock'       => $variant['stock'],
                    'sku'         => $variant['sku'],
                    'active'      => 1,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.products.create')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        $regions = Region::all();
        $variants = Variant::where('product_id', $product->id)->get();
        $images = ProductImage::where('product_id', $product->id)->get();

        return view('backend.products.edit', compact('product', 'categories', 'regions', 'variants', 'images'));
    }

    public function update(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Không validate, chỉ update luôn
        // Update product
        $product->name        = $request->name;
        $product->slug        = Str::slug($request->name) . '-' . time();
        $product->category_id = $request->category_id;
        $product->region_id   = $request->region_id;
        $product->description = $request->description;
        $product->origin      = $request->origin;
        $product->active      = $request->active ? 1 : 0;

        // Nếu upload ảnh đại diện mới
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '-' . time() . '.' . $file->getClientOriginalExtension();
            $imgPath = $file->storeAs('products', $fileName, 'public');
            $product->image = $imgPath;
        }
        $product->save();

        // Xử lý ảnh mô tả mới (nếu có)
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ nếu muốn
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($request->file('images') as $img) {
                $imgName = Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . rand(10, 99) . '.' . $img->getClientOriginalExtension();
                $imgPathDetail = $img->storeAs('products', $imgName, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $imgPathDetail,
                ]);
            }
        }

        // Xóa và cập nhật biến thể mới
        Variant::where('product_id', $product->id)->delete();
        if ($request->variants) {
            foreach ($request->variants as $variant) {
                Variant::create([
                    'product_id'  => $product->id,
                    'name'        => $variant['name'],
                    'description' => $variant['description'] ?? null,
                    'price'       => $variant['price'],
                    'stock'       => $variant['stock'],
                    'sku'         => $variant['sku'],
                    'active'      => 1,
                ]);
            }
        }

        return redirect()->route('admin.products.show', $product->slug)->with('success', 'Cập nhật sản phẩm thành công!');
    }
}
