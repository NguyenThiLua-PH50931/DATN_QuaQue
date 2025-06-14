<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attribute as AdminAttribute;
use App\Models\admin\Category as AdminCategory;
use App\Models\admin\Product as AdminProduct;
use App\Models\admin\Region as AdminRegion;
use App\Models\admin\Variant as AdminVariant;
use App\Models\admin\ProductImage;
use App\Models\admin\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Trang danh sách sản phẩm (sơ lược)
    public function index()
    {
        // Lấy danh sách sản phẩm, gắn category và variant đầu tiên (active)
        $products = AdminProduct::with([
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
        $product = AdminProduct::where('slug', $slug)
            ->with(['category', 'variants', 'reviews.user'])
            ->firstOrFail();

        return view('backend.products.show', compact('product'));
    }
    public function toggleVariantStatus($id)
    {
        $variant = AdminVariant::findOrFail($id);
        $variant->active = $variant->active ? 0 : 1;
        $variant->save();

        return back()->with('success', 'Đã cập nhật trạng thái biến thể!');
    }
    public function toggleStatus($id)
    {
        $product = AdminProduct::findOrFail($id);
        $product->active = !$product->active;
        $product->save();

        return back()->with('success', 'Đã cập nhật trạng thái sản phẩm!');
    }
    public function bulkDelete(Request $request)
    {
        AdminProduct::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Đã xóa các sản phẩm đã chọn!');
    }
    public function destroy($id)
    {
        $product = AdminProduct::findOrFail($id);
        $product->delete(); // Nếu muốn soft delete thì thêm SoftDeletes ở model Product
        return back()->with('success', 'Đã xóa sản phẩm "' . $product->name . '"!');
    }
    public function create()
    {
        $categories = AdminCategory::all();
        $regions = AdminRegion::all();
        $attributes = AdminAttribute::with('values')->get(); // lấy luôn giá trị thuộc tính

        return view('backend.products.create', compact('categories', 'regions', 'attributes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'region_id' => 'required|integer|exists:regions,id',
            'image' => 'required|image|max:2048',
            'origin' => 'required|string|max:255',
            'description' => 'nullable|string',

            'variants' => 'required|array|min:1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.image' => 'nullable|image|max:2048',
            'variants.*.description' => 'nullable|string',

            'variants.*.attribute_value_ids' => 'required|array|min:1',
            'variants.*.attribute_value_ids.*' => 'required|integer|exists:attribute_values,id',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm bắt buộc',
            'origin.required' => 'Xuất xứ sản phẩm bắt buộc',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'region_id.required' => 'Vui lòng chọn vùng miền',
            'image.required' => 'Vui lòng chọn ảnh đại diện',
            'variants.required' => 'Cần ít nhất 1 biến thể',
            'variants.*.price.required' => 'Giá biến thể bắt buộc',
            'variants.*.stock.required' => 'Tồn kho biến thể bắt buộc',
            'variants.*.attribute_value_ids.required' => 'Phải chọn giá trị thuộc tính cho biến thể',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // Upload ảnh đại diện
            $imgPath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imgPath = $file->storeAs('products', $fileName, 'public');
            }

            // Tạo sản phẩm
            $product = AdminProduct::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . time(),
                'category_id' => $request->category_id,
                'region_id' => $request->region_id,
                'description' => $request->description,
                'image' => $imgPath,
                'origin' => $request->origin,
                'active' => $request->active ? 1 : 0,
            ]);

            // Ảnh mô tả nhiều ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imgName = Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME))
                        . '-' . time() . rand(10, 99) . '.' . $img->getClientOriginalExtension();
                    $imgPathDetail = $img->storeAs('products', $imgName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imgPathDetail,
                    ]);
                }
            }

            // Lưu biến thể và liên kết attribute_values
            foreach ($request->variants as $variantData) {
                $variantImagePath = null;
                if (!empty($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantImgFile = $variantData['image'];
                    $variantImgName = Str::slug('variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                    $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');
                }

                // Lấy tên biến thể (chuỗi nối các giá trị thuộc tính)
                $attributeValues = AttributeValue::whereIn('id', $variantData['attribute_value_ids'])->get();
                $variantName = $attributeValues->pluck('value')->implode(' - ');

                // Tạo biến thể
                $variant = AdminVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'] ?? Str::upper('SKU-' . uniqid()),
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'name' => $variantName,
                    'description' => $variantData['description'] ?? null,
                    'image' => $variantImagePath,
                    'active' => 1,
                ]);

                // Tạo mảng pivot [attribute_value_id => ['attribute_id' => xxx]]
                $pivotData = [];
                foreach ($attributeValues as $attrVal) {
                    $pivotData[$attrVal->id] = ['attribute_id' => $attrVal->attribute_id];
                }

                // Sync pivot liên kết biến thể với các giá trị thuộc tính
                $variant->attributeValues()->sync($pivotData);
            }

            DB::commit();

            return redirect()->route('admin.products.create')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function deleteImage(Request $request)
    {
        $img = ProductImage::find($request->id);
        if (!$img) return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh']);
        // Xóa file vật lý nếu có
        if (Storage::disk('public')->exists($img->image_url)) {
            Storage::disk('public')->delete($img->image_url);
        }
        $img->delete();
        return response()->json(['success' => true]);
    }
    public function edit($slug)
    {
        $product = AdminProduct::where('slug', $slug)
            ->with(['product_images', 'variants.attributeValues'])
            ->firstOrFail();

        $categories = AdminCategory::all();
        $regions = AdminRegion::all();
        $attributes = AdminAttribute::with('values')->get();

        return view('backend.products.edit', compact('product', 'categories', 'regions', 'attributes'));
    }

    public function update(Request $request, $slug)
    {
        $product = AdminProduct::where('slug', $slug)->firstOrFail();

        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'region_id' => 'required|integer|exists:regions,id',
            'image' => 'nullable|image|max:2048',
            'origin' => 'required|string|max:255',
            'description' => 'nullable|string',

            'variants' => 'required|array|min:1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.image' => 'nullable|image|max:2048',
            'variants.*.old_image' => 'nullable|string',
            'variants.*.description' => 'nullable|string',

            'variants.*.attribute_value_ids' => 'required|array|min:1',
            'variants.*.attribute_value_ids.*' => 'required|integer|exists:attribute_values,id',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm bắt buộc',
            'origin.required' => 'Xuất xứ sản phẩm bắt buộc',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'region_id.required' => 'Vui lòng chọn vùng miền',
            'variants.required' => 'Cần ít nhất 1 biến thể',
            'variants.*.price.required' => 'Giá biến thể bắt buộc',
            'variants.*.stock.required' => 'Tồn kho biến thể bắt buộc',
            'variants.*.attribute_value_ids.required' => 'Phải chọn giá trị thuộc tính cho biến thể',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            // 1. Cập nhật ảnh đại diện nếu upload mới
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $file = $request->file('image');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imgPath = $file->storeAs('products', $fileName, 'public');
                $product->image = $imgPath;
            }

            // 2. Cập nhật các trường sản phẩm khác
            $product->name = $request->name;
            $product->slug = Str::slug($request->name) . '-' . time();
            $product->category_id = $request->category_id;
            $product->region_id = $request->region_id;
            $product->description = $request->description;
            $product->origin = $request->origin;
            $product->active = $request->active ? 1 : 0;

            $product->save();

            // 3. Thêm ảnh mô tả mới nếu có (ảnh cũ giữ nguyên)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imgName = Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME))
                        . '-' . time() . rand(10, 99) . '.' . $img->getClientOriginalExtension();
                    $imgPathDetail = $img->storeAs('products', $imgName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imgPathDetail,
                    ]);
                }
            }

            // 4. Xóa biến thể cũ cùng ảnh cũ nếu có
            foreach ($product->variants as $oldVariant) {
                if ($oldVariant->image) {
                    Storage::disk('public')->delete($oldVariant->image);
                }
                $oldVariant->attributeValues()->detach();
                $oldVariant->delete();
            }

            // 5. Tạo lại biến thể mới, giữ ảnh cũ nếu không upload ảnh mới
            foreach ($request->variants as $variantData) {
                $variantImagePath = null;

                if (!empty($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    // Upload ảnh mới
                    $variantImgFile = $variantData['image'];
                    $variantImgName = Str::slug('variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                    $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');
                } else if (!empty($variantData['old_image'])) {
                    // Giữ lại ảnh cũ nếu không upload ảnh mới
                    $variantImagePath = $variantData['old_image'];
                }

                // Lấy tên biến thể (chuỗi nối các giá trị thuộc tính)
                $attributeValues = AttributeValue::whereIn('id', $variantData['attribute_value_ids'])->get();
                $variantName = $attributeValues->pluck('value')->implode(' - ');

                // Tạo biến thể mới
                $variant = AdminVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'] ?? Str::upper('SKU-' . uniqid()),
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'name' => $variantName,
                    'description' => $variantData['description'] ?? null,
                    'image' => $variantImagePath,
                    'active' => 1,
                ]);

                // Tạo mảng pivot [attribute_value_id => ['attribute_id' => xxx]]
                $pivotData = [];
                foreach ($attributeValues as $attrVal) {
                    $pivotData[$attrVal->id] = ['attribute_id' => $attrVal->attribute_id];
                }

                // Sync pivot liên kết biến thể với các giá trị thuộc tính
                $variant->attributeValues()->sync($pivotData);
            }

            DB::commit();

            return redirect()->route('admin.products.edit', $product->slug)->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function getDescription($id)
    {
        $product = AdminProduct::findOrFail($id);
        return response($product->description);
    }
    public function getVariantDescription($id)
    {
        $variant = AdminVariant::find($id);
        if (!$variant) {
            return response('Không tìm thấy biến thể', 404);
        }

        // Trả về mô tả (HTML) của biến thể
        return response($variant->description ?? '<em>Chưa có mô tả</em>');
    }
}
