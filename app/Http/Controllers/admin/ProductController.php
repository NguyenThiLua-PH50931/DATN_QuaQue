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
        $attributes = AdminAttribute::with('values')->get(); // Lấy luôn các value của attribute

        return view('backend.products.create', compact('categories', 'regions', 'attributes'));
    }

    public function store(Request $request)
    {
        // validate dữ liệu đầu vào
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'region_id' => 'required|integer|exists:regions,id',
            'image' => 'required|image|max:2048',
            'origin'  => 'required|string|max:255',
            'description' => 'nullable|string',
            // validate biến thể
            'variants' => 'required|array|min:1',
            'variants.*.attribute_value_id' => 'required|exists:attribute_values,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
        ];
        $messages = [
            'name.required' => 'Tên sản phẩm bắt buộc',
            'origin.required' => 'Xuất xứ sản phẩm bắt buộc',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'region_id.required' => 'Vui lòng chọn vùng miền',
            'image.required' => 'Vui lòng chọn ảnh đại diện',
            'variants.required' => 'Cần ít nhất 1 biến thể',
            'variants.*.attribute_value_id.required' => 'Phải chọn giá trị thuộc tính cho biến thể',
            'variants.*.price.required' => 'Giá biến thể bắt buộc',
            'variants.*.stock.required' => 'Tồn kho biến thể bắt buộc',
        ];
        $validated = $request->validate($rules, $messages);

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

            // Lưu sản phẩm
            $product = AdminProduct::create([
                'name'        => $request->name,
                'slug'        => Str::slug($request->name) . '-' . time(),
                'category_id' => $request->category_id,
                'region_id'   => $request->region_id,
                'description' => $request->description,
                'image'       => $imgPath,
                'origin'      => $request->origin,
                'active'      => $request->active ? 1 : 0,
            ]);

            // Ảnh mô tả (nhiều ảnh phụ)
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

            // Lưu các biến thể (variants)
            foreach ($request->variants as $i => $variant) {
                // Lấy giá trị attribute_value
                $attributeValue = AttributeValue::findOrFail($variant['attribute_value_id']);
                // Upload ảnh biến thể (nếu có)
                $variantImagePath = null;
                if (isset($variant['image']) && $variant['image']) {
                    $variantImgFile = $variant['image'];
                    $variantImgName = Str::slug($attributeValue->value ?? 'variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                    $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');
                }

                AdminVariant::create([
                    'product_id'         => $product->id,
                    'attribute_value_id' => $attributeValue->id,
                    'name'               => $attributeValue->value,
                    'description'        => $variant['description'] ?? null,
                    'price'              => $variant['price'],
                    'stock'              => $variant['stock'],
                    'sku'                => $variant['sku'] ?? Str::upper('SKU-' . uniqid()),
                    'image'              => $variantImagePath,
                    'active'             => 1,
                ]);
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
        $product = AdminProduct::with('variants')->where('slug', $slug)->firstOrFail();
        $categories = AdminCategory::all();
        $regions = AdminRegion::all();
        $attributes = AdminAttribute::with('values')->get();
        $product = AdminProduct::with(['variants', 'images'])->where('slug', $slug)->firstOrFail();
        return view('backend.products.edit', compact('product', 'categories', 'regions', 'attributes'));
    }


    public function update(Request $request, $slug)
    {
        $product = AdminProduct::with('variants')->where('slug', $slug)->firstOrFail();

        // Lấy id các biến thể cũ để xử lý xóa những cái bị bỏ đi
        $oldVariantIds = $product->variants->pluck('id')->toArray();

        // Validate (tương tự store)
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'region_id' => 'required|integer|exists:regions,id',
            'origin'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            // validate biến thể
            'variants' => 'required|array|min:1',
            'variants.*.attribute_value_id' => 'required|exists:attribute_values,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
        ];
        $messages = [
            'name.required' => 'Tên sản phẩm bắt buộc',
            'origin.required' => 'Xuất xứ sản phẩm bắt buộc',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'region_id.required' => 'Vui lòng chọn vùng miền',
            'variants.required' => 'Cần ít nhất 1 biến thể',
            'variants.*.attribute_value_id.required' => 'Phải chọn giá trị thuộc tính cho biến thể',
            'variants.*.price.required' => 'Giá biến thể bắt buộc',
            'variants.*.stock.required' => 'Tồn kho biến thể bắt buộc',
        ];
        $validated = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // Check nếu không thay đổi gì thì return luôn (không báo lỗi)
            $dataChanged = false;

            // So sánh trường chính sản phẩm
            $productChanged = (
                $request->name !== $product->name ||
                $request->category_id != $product->category_id ||
                $request->region_id != $product->region_id ||
                $request->origin !== $product->origin ||
                $request->description !== $product->description ||
                $request->active != $product->active ||
                $request->hasFile('image')
            );
            if ($productChanged) $dataChanged = true;

            // Xử lý ảnh đại diện
            $imgPath = $product->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imgPath = $file->storeAs('products', $fileName, 'public');
                $dataChanged = true;
            }
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    if (!$img->isValid()) continue;
                    $imgName = Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME))
                        . '-' . time() . rand(10, 99) . '.' . $img->getClientOriginalExtension();
                    $imgPathDetail = $img->storeAs('products', $imgName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => $imgPathDetail,
                    ]);
                }
            }
            // Cập nhật sản phẩm nếu có thay đổi
            if ($productChanged) {
                $product->update([
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name) . '-' . time(),
                    'category_id' => $request->category_id,
                    'region_id'   => $request->region_id,
                    'description' => $request->description,
                    'image'       => $imgPath,
                    'origin'      => $request->origin,
                    'active'      => $request->active ? 1 : 0,
                ]);
            }

            // Xử lý biến thể: update/cập nhật/xóa/tạo mới
            $keepVariantIds = [];
            foreach ($request->variants as $i => $variant) {
                // Kiểm tra nếu có id là update, không thì tạo mới
                if (isset($variant['id']) && $variant['id']) {
                    $v = AdminVariant::where('id', $variant['id'])->where('product_id', $product->id)->first();
                    if ($v) {
                        // Check có thay đổi gì không
                        $variantChanged = (
                            $v->attribute_value_id != $variant['attribute_value_id'] ||
                            $v->price != $variant['price'] ||
                            $v->stock != $variant['stock'] ||
                            ($variant['sku'] ?? '') != $v->sku ||
                            ($variant['description'] ?? '') != $v->description ||
                            isset($variant['image']) // nếu upload ảnh mới
                        );
                        // Upload ảnh mới nếu có
                        $variantImagePath = $v->image;
                        if (isset($variant['image']) && $variant['image']) {
                            $variantImgFile = $variant['image'];
                            $attributeValue = AttributeValue::find($variant['attribute_value_id']);
                            $variantImgName = Str::slug($attributeValue->value ?? 'variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                            $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');
                            $variantChanged = true;
                        }
                        // Update nếu có thay đổi
                        if ($variantChanged) {
                            $v->update([
                                'attribute_value_id' => $variant['attribute_value_id'],
                                'name'               => $attributeValue->value,
                                'description'        => $variant['description'] ?? null,
                                'price'              => $variant['price'],
                                'stock'              => $variant['stock'],
                                'sku'                => $variant['sku'] ?? Str::upper('SKU-' . uniqid()),
                                'image'              => $variantImagePath,
                            ]);
                            $dataChanged = true;
                        }
                        $keepVariantIds[] = $v->id;
                    }
                } else {
                    // Thêm mới biến thể
                    $attributeValue = AttributeValue::findOrFail($variant['attribute_value_id']);
                    $variantImagePath = null;
                    if (isset($variant['image']) && $variant['image']) {
                        $variantImgFile = $variant['image'];
                        $variantImgName = Str::slug($attributeValue->value ?? 'variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                        $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');
                    }
                    $newVariant = AdminVariant::create([
                        'product_id'         => $product->id,
                        'attribute_value_id' => $attributeValue->id,
                        'name'               => $attributeValue->value,
                        'description'        => $variant['description'] ?? null,
                        'price'              => $variant['price'],
                        'stock'              => $variant['stock'],
                        'sku'                => $variant['sku'] ?? Str::upper('SKU-' . uniqid()),
                        'image'              => $variantImagePath,
                        'active'             => 1,
                    ]);
                    $keepVariantIds[] = $newVariant->id;
                    $dataChanged = true;
                }
            }
            // Xóa những biến thể không còn trong form
            $deletedIds = array_diff($oldVariantIds, $keepVariantIds);
            if (!empty($deletedIds)) {
                AdminVariant::whereIn('id', $deletedIds)->delete();
                $dataChanged = true;
            }

            DB::commit();

            // Nếu không thay đổi gì, chỉ reload lại trang, không báo lỗi
            if (!$dataChanged) {
                return redirect()->back()->with('info', 'Không có thay đổi nào được lưu!');
            }

            return redirect()->route('admin.products.edit', $product->slug)->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
