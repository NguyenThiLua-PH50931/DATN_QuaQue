<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attribute as AdminAttribute;
use App\Models\admin\Category as AdminCategory;
use App\Models\admin\Product as AdminProduct;
use App\Models\admin\Region as AdminRegion;
use App\Models\admin\ProductVariant as AdminProductVariant;
use App\Models\admin\ProductImage;
use App\Models\admin\AttributeValue;
use App\Models\Client\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = AdminProduct::where('name', 'like', "%$query%")
            ->orWhereHas('region', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->select('id', 'name', 'slug', 'image')
            ->get();

        return response()->json($products);
    }

    public function searchPage(Request $request)
    {
        $queryText = $request->input('search');
        $categoryId = $request->input('category_id');
        $regionId = $request->input('region_id');
        $priceMin = $request->input('price_min', 0);
        $priceMax = $request->input('price_max', 10000000);
        $rating = $request->input('rating');
        $sort = $request->input('sort');

        // Sử dụng model phía client
        $productsQuery = \App\Models\Client\Product::with([
            'category',
            'region',
            'images',
            'variants' => function ($q) {
                $q->where('active', 1)->orderBy('id');
            },
            'reviews'
        ])
            ->where('active', 1)
            ->where(function ($q) use ($queryText) {
                $q->where('name', 'like', "%$queryText%")
                    ->orWhereHas('region', function ($regionQuery) use ($queryText) {
                        $regionQuery->where('name', 'like', "%$queryText%");
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($queryText) {
                        $categoryQuery->where('name', 'like', "%$queryText%");
                    });
            });

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        if ($regionId) {
            $productsQuery->where('region_id', $regionId);
        }
        // Lọc theo khoảng giá (dựa vào variants)
        $productsQuery->whereHas('variants', function ($q) use ($priceMin, $priceMax) {
            $q->whereBetween('price', [$priceMin, $priceMax]);
        });
        // Lọc theo rating trung bình
        if ($rating) {
            $productsQuery->withAvg('reviews', 'rating')
                ->havingRaw('ROUND(reviews_avg_rating) = ?', [$rating]);
        }
        // Sắp xếp
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_variants.product_id = products.id) ASC');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('(SELECT MAX(price) FROM product_variants WHERE product_variants.product_id = products.id) DESC');
                break;
            case 'rating':
                $productsQuery->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            default:
                $productsQuery->orderByDesc('id');
        }

        $products = $productsQuery->paginate(12)->appends($request->except('page'));

        // Lấy min/max giá cho slider
        $allPrices = \App\Models\Client\ProductVariant::where('active', 1)->pluck('price');
        $priceMinAll = $allPrices->min() ?? 0;
        $priceMaxAll = $allPrices->max() ?? 10000000;

        $categories = \App\Models\Client\Category::all();
        $regions = \App\Models\Client\Region::all();

        return view('frontend.products.search', [
            'products' => $products,
            'query' => $queryText,
            'categories' => $categories,
            'regions' => $regions,
            'priceMin' => $priceMinAll,
            'priceMax' => $priceMaxAll,
        ]);
    }
    // Trang danh sách sản phẩm (sơ lược)
    public function index(Request $request)
    {
        $query = AdminProduct::with([
            'category',
            'region',
            'product_images',
            'variants' => function ($q) {
                $q->where('active', 1)->orderBy('id');
            }
        ]);

        // Lọc theo trạng thái (bao gồm cả sản phẩm đã xóa mềm)
        if ($request->has('trashed') && $request->trashed == 'true') {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed(); // Chỉ lấy các sản phẩm không bị xóa mềm
        }

        // Áp dụng các bộ lọc khác (đã có từ trước nếu có)
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('region')) {
            $query->where('region_id', $request->region);
        }
        if ($request->filled('status')) {
            $query->where('active', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('origin', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderByDesc('id')->get();

        $categories = AdminCategory::all();
        $regions = AdminRegion::all();

        return view('backend.products.index', compact('products', 'categories', 'regions'));
    }

    // Trang chi tiết sản phẩm theo slug
    public function show($slug)
    {
        $product = AdminProduct::where('slug', $slug)
            ->with(['category', 'region', 'product_images', 'variants.attributeValues', 'reviews.user', 'comments'])
            ->firstOrFail();

        return view('backend.products.show', compact('product'));
    }

    // Chuyển đổi trạng thái biến thể
    public function toggleVariantStatus($id)
    {
        $variant = AdminProductVariant::findOrFail($id);
        $variant->active = $variant->active ? 0 : 1;
        $variant->save();

        return back()->with('success_modal', 'Đã cập nhật trạng thái biến thể!');
    }

    // Chuyển đổi trạng thái sản phẩm
    public function toggleStatus($id)
    {
        $product = AdminProduct::findOrFail($id);
        $product->active = !$product->active;
        $product->save();

        return back()->with('success_modal', 'Đã cập nhật trạng thái sản phẩm!');
    }

    // Xóa mềm một sản phẩm
    public function destroy($id)
    {
        $product = AdminProduct::findOrFail($id);

        // Xóa mềm bình luận và phản hồi liên quan
        foreach ($product->comments as $comment) {
            $comment->replies()->delete(); // Xóa mềm các phản hồi
            $comment->delete(); // Xóa mềm bình luận
        }

        $product->delete(); // Xóa mềm sản phẩm
        return back()->with('success_modal', 'Đã xóa mềm sản phẩm "' . $product->name . '" và các bình luận liên quan!');
    }

    // Xóa mềm nhiều sản phẩm
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        $ids = array_filter($ids, 'is_numeric');

        if (empty($ids)) {
            return back()->with('error', 'Không có sản phẩm nào được chọn hoặc ID không hợp lệ.');
        }

        DB::beginTransaction();
        try {
            $products = AdminProduct::whereIn('id', $ids)->get();

            foreach ($products as $product) {
                // Xóa mềm bình luận và phản hồi liên quan
                foreach ($product->comments as $comment) {
                    $comment->replies()->delete(); // Xóa mềm các phản hồi
                    $comment->delete(); // Xóa mềm bình luận
                }
                $product->delete(); // Xóa mềm sản phẩm
            }

            DB::commit();
            return back()->with('success_modal', 'Đã xóa mềm ' . count($ids) . ' sản phẩm và các bình luận liên quan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi xóa mềm hàng loạt: ' . $e->getMessage());
        }
    }

    // Khôi phục một sản phẩm đã xóa mềm
    public function restore($id)
    {
        $product = AdminProduct::withTrashed()->findOrFail($id);
        $product->restore();
        return response()->json(['success' => true, 'message' => 'Đã khôi phục sản phẩm "' . $product->name . '"!']);
    }

    // Khôi phục nhiều sản phẩm đã xóa mềm
    public function bulkRestore(Request $request)
    {
        $ids = explode(',', $request->ids);
        AdminProduct::withTrashed()->whereIn('id', $ids)->restore();
        return response()->json(['success' => true, 'message' => 'Đã khôi phục ' . count($ids) . ' sản phẩm đã chọn!']);
    }

    // Xóa cứng một sản phẩm
    public function forceDelete($id)
    {
        DB::beginTransaction();
        try {
            $product = AdminProduct::withTrashed()->findOrFail($id);

            // Xóa tất cả bình luận và phản hồi liên quan
            $comments = $product->comments()->get();
            foreach ($comments as $comment) {
                $comment->replies()->delete(); // Xóa các phản hồi
                $comment->delete(); // Xóa bình luận
            }

            // Xóa tất cả ảnh mô tả liên quan
            foreach ($product->product_images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->delete();
            }

            // Xóa tất cả biến thể và ảnh biến thể liên quan (bao gồm cả đã xóa mềm)
            foreach (AdminProductVariant::where('product_id', $product->id)->withTrashed()->get() as $variant) {
                if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variant->attributeValues()->detach(); // Ngắt liên kết với attribute_values
                $variant->forceDelete(); // Xóa cứng biến thể
            }

            // Xóa ảnh đại diện sản phẩm
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->forceDelete(); // Xóa cứng sản phẩm chính

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã xóa vĩnh viễn sản phẩm "' . $product->name . '" và các bình luận liên quan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi xóa vĩnh viễn sản phẩm: ' . $e->getMessage()], 500);
        }
    }

    // Xóa cứng nhiều sản phẩm
    public function bulkForceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = explode(',', $request->ids);
            $ids = array_filter($ids, 'is_numeric');

            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Không có sản phẩm nào được chọn hoặc ID không hợp lệ.'], 400);
            }

            $products = AdminProduct::withTrashed()->whereIn('id', $ids)->get();

            if ($products->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm nào trong thùng rác với các ID đã chọn.'], 400);
            }

            foreach ($products as $product) {
                // Xóa cứng bình luận và phản hồi liên quan
                $comments = $product->comments()->get();
                foreach ($comments as $comment) {
                    $comment->replies()->delete(); // Xóa các phản hồi
                    $comment->delete(); // Xóa bình luận
                }

                // Xóa tất cả ảnh mô tả liên quan
                foreach ($product->product_images as $image) {
                    if (Storage::disk('public')->exists($image->image_url)) {
                        Storage::disk('public')->delete($image->image_url);
                    }
                    $image->delete();
                }

                // Xóa tất cả biến thể và ảnh biến thể liên quan (bao gồm cả đã xóa mềm)
                foreach (AdminProductVariant::where('product_id', $product->id)->withTrashed()->get() as $variant) {
                    if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->attributeValues()->detach(); // Ngắt liên kết với attribute_values
                    $variant->forceDelete(); // Xóa cứng biến thể
                }

                // Xóa ảnh đại diện sản phẩm
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->forceDelete(); // Xóa cứng sản phẩm chính
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã xóa vĩnh viễn ' . count($ids) . ' sản phẩm và các bình luận liên quan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi xóa vĩnh viễn hàng loạt: ' . $e->getMessage()], 500);
        }
    }

    // Trang thùng rác sản phẩm
    public function trashed()
    {
        $products = AdminProduct::onlyTrashed()->orderByDesc('deleted_at')->paginate(10);
        return view('backend.products.trashed', compact('products'));
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
            'has_variants' => 'required|boolean',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm bắt buộc',
            'origin.required' => 'Xuất xứ sản phẩm bắt buộc',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'region_id.required' => 'Vui lòng chọn vùng miền',
            'image.required' => 'Vui lòng chọn ảnh đại diện',
            'has_variants.required' => 'Vui lòng chọn loại sản phẩm',
        ];

        // Thêm validation rules cho biến thể nếu sản phẩm có biến thể
        if ($request->has_variants) {
            $rules['variants'] = 'required|array|min:1';
            $rules['variants.*.price'] = 'required|numeric|min:0';
            $rules['variants.*.stock'] = 'required|integer|min:0';
            $rules['variants.*.sku'] = 'nullable|string|max:255';
            $rules['variants.*.image'] = 'nullable|image|max:2048';
            $rules['variants.*.description'] = 'nullable|string';
            $rules['variants.*.attribute_value_ids'] = 'required|array|min:1';
            $rules['variants.*.attribute_value_ids.*'] = 'required|integer|exists:attribute_values,id';

            $messages['variants.required'] = 'Cần ít nhất 1 biến thể';
            $messages['variants.*.price.required'] = 'Giá biến thể bắt buộc';
            $messages['variants.*.stock.required'] = 'Tồn kho biến thể bắt buộc';
            $messages['variants.*.attribute_value_ids.required'] = 'Phải chọn giá trị thuộc tính cho biến thể';
        } else {
            // Validation cho sản phẩm không có biến thể
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock'] = 'required|integer|min:0';
            $rules['sku'] = 'nullable|string|max:255';

            $messages['price.required'] = 'Giá sản phẩm bắt buộc';
            $messages['stock.required'] = 'Tồn kho sản phẩm bắt buộc';
        }

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
                'has_variants' => $request->has_variants ? 1 : 0,
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

            if ($request->has_variants) {
                // Xử lý biến thể
                foreach ($request->variants as $variantData) {
                    $variantImagePath = null;
                    if (!empty($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $variantImgFile = $variantData['image'];
                        $variantImgName = Str::slug('variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                        $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');

                        // Debug: Log đường dẫn ảnh
                        \Log::info('Variant image saved: ' . $variantImagePath);
                    }

                    $attributeValues = AttributeValue::whereIn('id', $variantData['attribute_value_ids'])->get();
                    $variantName = $attributeValues->pluck('value')->implode(' - ');

                    $variant = AdminProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'] ?? Str::upper('SKU-' . uniqid()),
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'name' => $variantName,
                        'description' => $variantData['description'] ?? null,
                        'image' => $variantImagePath,
                        'active' => 1,
                    ]);

                    // Debug: Log thông tin biến thể đã tạo
                    \Log::info('Variant created: ' . $variant->id . ' with image: ' . $variant->image);

                    $pivotData = [];
                    foreach ($attributeValues as $attrVal) {
                        $pivotData[$attrVal->id] = ['attribute_id' => $attrVal->attribute_id];
                    }

                    $variant->attributeValues()->sync($pivotData);
                }
            } else {
                // Tạo một biến thể mặc định cho sản phẩm không có biến thể
                AdminProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $request->sku ?? Str::upper('SKU-' . uniqid()),
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'name' => $request->variant_name ?? 'Mặc định',
                    'description' => null,
                    'image' => null,
                    'active' => 1,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function deleteImage($id)
    {
        $img = ProductImage::find($id);
        if (!$img) return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh']);
        // Xóa file vật lý nếu có
        if (Storage::disk('public')->exists($img->image_url)) {
            Storage::disk('public')->delete($img->image_url);
        }
        $img->delete();
        return response()->json(['success' => true, 'message' => 'Đã xóa ảnh thành công!']);
    }

    public function edit($slug)
    {
        $product = AdminProduct::where('slug', $slug)
            ->with(['product_images', 'variants.attributeValues'])
            ->firstOrFail();

        // Debug: Log thông tin variants để kiểm tra
        \Log::info('Product variants:', $product->variants->toArray());

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
            'has_variants' => 'required|boolean',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm bắt buộc',
            'origin.required' => 'Xuất xứ sản phẩm bắt buộc',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'region_id.required' => 'Vui lòng chọn vùng miền',
            'has_variants.required' => 'Vui lòng chọn loại sản phẩm',
        ];

        // Thêm validation rules cho biến thể nếu sản phẩm có biến thể
        if ($request->has_variants) {
            $rules['variants'] = 'required|array|min:1';
            $rules['variants.*.price'] = 'required|numeric|min:0';
            $rules['variants.*.stock'] = 'required|integer|min:0';
            $rules['variants.*.sku'] = 'nullable|string|max:255';
            $rules['variants.*.image'] = 'nullable|image|max:2048';
            $rules['variants.*.old_image'] = 'nullable|string';
            $rules['variants.*.description'] = 'nullable|string';
            $rules['variants.*.attribute_value_ids'] = 'required|array|min:1';
            $rules['variants.*.attribute_value_ids.*'] = 'required|integer|exists:attribute_values,id';

            $messages['variants.required'] = 'Cần ít nhất 1 biến thể';
            $messages['variants.*.price.required'] = 'Giá biến thể bắt buộc';
            $messages['variants.*.stock.required'] = 'Tồn kho biến thể bắt buộc';
            $messages['variants.*.attribute_value_ids.required'] = 'Phải chọn giá trị thuộc tính cho biến thể';
        } else {
            // Validation cho sản phẩm không có biến thể
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock'] = 'required|integer|min:0';
            $rules['sku'] = 'nullable|string|max:255';

            $messages['price.required'] = 'Giá sản phẩm bắt buộc';
            $messages['stock.required'] = 'Tồn kho sản phẩm bắt buộc';
        }

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            // 1. Cập nhật ảnh đại diện nếu upload mới
            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
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
            $product->category_id = $request->category_id;
            $product->region_id = $request->region_id;
            $product->description = $request->description;
            $product->origin = $request->origin;
            $product->active = $request->active ? 1 : 0;
            $product->has_variants = $request->has_variants ? 1 : 0;
            $product->save();

            // 3. Thêm ảnh mô tả mới nếu có
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

            // 4. Thêm ảnh mô tả mới nếu có
            if ($request->hasFile('description_images')) {
                foreach ($request->file('description_images') as $img) {
                    $imgName = Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME))
                        . '-' . time() . rand(10, 99) . '.' . $img->getClientOriginalExtension();
                    $imgPathDetail = $img->storeAs('products', $imgName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imgPathDetail,
                    ]);
                }
            }

                        // 5. Xử lý biến thể
            if ($request->has_variants) {
                // Lấy danh sách biến thể hiện tại để so sánh
                $existingVariants = $product->variants->keyBy('id');
                $updatedVariantIds = [];

                // Cập nhật hoặc tạo mới biến thể
                foreach ($request->variants as $variantData) {
                    $variantImagePath = null;

                    // Xử lý ảnh biến thể
                    if (!empty($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        // Có ảnh mới upload
                        $variantImgFile = $variantData['image'];
                        $variantImgName = Str::slug('variant') . '-' . time() . '-' . uniqid() . '.' . $variantImgFile->getClientOriginalExtension();
                        $variantImagePath = $variantImgFile->storeAs('products/variants', $variantImgName, 'public');
                    } else if (!empty($variantData['old_image'])) {
                        // Giữ lại ảnh cũ
                        $variantImagePath = $variantData['old_image'];
                    }

                    $attributeValues = AttributeValue::whereIn('id', $variantData['attribute_value_ids'])->get();
                    $variantName = $attributeValues->pluck('value')->implode(' - ');

                    // Kiểm tra xem biến thể đã tồn tại chưa
                    if (!empty($variantData['id']) && $existingVariants->has($variantData['id'])) {
                        // Cập nhật biến thể hiện có
                        $variant = $existingVariants->get($variantData['id']);
                        $variant->update([
                            'sku' => $variantData['sku'] ?? $variant->sku,
                            'price' => $variantData['price'],
                            'stock' => $variantData['stock'],
                            'name' => $variantName,
                            'description' => $variantData['description'] ?? $variant->description,
                            'image' => $variantImagePath ?: $variant->image, // Chỉ cập nhật nếu có ảnh mới
                            'active' => 1,
                        ]);
                        $updatedVariantIds[] = $variant->id;
                    } else {
                        // Tạo biến thể mới
                        $variant = AdminProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $variantData['sku'] ?? Str::upper('SKU-' . uniqid()),
                            'price' => $variantData['price'],
                            'stock' => $variantData['stock'],
                            'name' => $variantName,
                            'description' => $variantData['description'] ?? null,
                            'image' => $variantImagePath,
                            'active' => 1,
                        ]);
                        $updatedVariantIds[] = $variant->id;
                    }

                    // Cập nhật quan hệ attribute_values
                    $pivotData = [];
                    foreach ($attributeValues as $attrVal) {
                        $pivotData[$attrVal->id] = ['attribute_id' => $attrVal->attribute_id];
                    }
                    $variant->attributeValues()->sync($pivotData);
                }

                // KHÔNG XÓA BIẾN THỂ NÀO - Giữ lại tất cả biến thể hiện có
                // Chỉ cập nhật thông tin, không xóa biến thể nào cả
            } else {
                // XÓA tất cả biến thể cũ trước khi tạo mới
                foreach ($product->variants as $variant) {
                    if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->attributeValues()->detach();
                    $variant->forceDelete();
                }
                // Tạo một biến thể mặc định cho sản phẩm không có biến thể
                AdminProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $request->sku ?? Str::upper('SKU-' . uniqid()),
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'name' => $request->variant_name ?? 'Mặc định',
                    'description' => null,
                    'image' => null,
                    'active' => 1,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cập nhật sản phẩm thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function getDescription($id)
    {
        $product = AdminProduct::findOrFail($id);
        return response($product->description);
    }

    public function getVariantDescription($id)
    {
        $variant = AdminProductVariant::find($id);
        if (!$variant) {
            return response('Không tìm thấy biến thể', 404);
        }

        return response($variant->description ?? '<em>Chưa có mô tả</em>');
    }
}
