# CÂU HỎI THI FRONTEND

## 1. Logic đưa banner vào đúng chỗ ở trang chủ

**Câu hỏi:** Ở trang chủ logic đưa banner vào đúng chỗ là như nào?

**Trả lời:** 
- **File:** `DATN_QuaQue/app/Http/Controllers/Client/ClientHomeController.php` (dòng 18-58)
- **Logic:** Sử dụng field `location` để xác định vị trí banner:
  - `main_hero_banner`: Banner chính lớn bên trái
  - `small_promo_banner_top`: Banner nhỏ phía trên bên phải  
  - `small_promo_banner_bottom`: Banner nhỏ phía dưới bên phải
  - `slider_banner`: Banner slider (tối đa 4 banner)
  - `product_section_promo_left_top`: Banner quảng cáo bên trái phía trên
  - `product_section_promo_left_bottom`: Banner quảng cáo bên trái phía dưới
  - `new_products_cashback_banner`: Banner cashback sản phẩm mới
  - `new_products_promo_left`: Banner quảng cáo sản phẩm mới bên trái
  - `new_products_promo_right`: Banner quảng cáo sản phẩm mới bên phải
  - `last_page_promo_banner`: Banner quảng cáo cuối trang

- **File view:** `DATN_QuaQue/resources/views/frontend/home.blade.php` (dòng 8-85)
- **Điều kiện hiển thị:** Banner phải `active = true` và thời gian hiện tại nằm trong khoảng `display_at` đến `display_end_at`

## 2. Logic chọn sản phẩm cho các mục ở trang chủ

**Câu hỏi:** Ở trang chủ cái logic nào để chọn các sản phẩm cho vào các mục: Sản Phẩm Nổi Bật, Sản phẩm mới, Sản phẩm bán chạy?

**Trả lời:**
- **File:** `DATN_QuaQue/app/Http/Controllers/Client/ClientHomeController.php`

### Sản phẩm nổi bật (dòng 60-95):
```php
$topViewedProducts = Product::orderBy('view_total', 'desc')->take(20)->get();
```
- Sắp xếp theo lượt xem tổng (`view_total`) giảm dần
- Lấy 20 sản phẩm đầu tiên, sau đó lọc còn hàng và lấy 8 sản phẩm

### Sản phẩm mới (dòng 97-150):
```php
$latestProducts = Product::latest()->take(20)->get();
```
- Sắp xếp theo thời gian tạo mới nhất (`created_at`)
- Lấy 20 sản phẩm đầu tiên, sau đó lọc còn hàng và lấy 12 sản phẩm

### Sản phẩm bán chạy (dòng 245-260):
```php
$bestSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
    ->join('order_items', 'order_items.product_id', '=', 'products.id')
    ->groupBy('products.id')
    ->orderByDesc('total_sold')
    ->take(12)
    ->get();
```
- Join với bảng `order_items` để tính tổng số lượng đã bán
- Sắp xếp theo `total_sold` giảm dần
- Lấy 12 sản phẩm bán chạy nhất

## 3. Logic hiển thị danh mục và lọc

**Câu hỏi:** Ở trang chủ cái logic để đưa danh mục ra là gì và làm như thế nào để chọn danh mục ở home mà có thể lọc luôn ở trang sản phẩm?

**Trả lời:**
- **File:** `DATN_QuaQue/app/Http/Controllers/Client/ClientHomeController.php` (dòng 152)
- **Logic hiển thị:** `$categories = Category::all();` - Lấy tất cả danh mục

- **File view:** `DATN_QuaQue/resources/views/frontend/home.blade.php` (dòng 90-144)
- **Cách lọc:** Sử dụng link với parameter `category_id`:
```php
<a href="{{ route('client.products.catalog', ['category_id' => $category->id]) }}">
```

- **File xử lý lọc:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 30-40)
```php
if ($request->filled('category_id')) {
    $categoryIds = is_array($request->category_id) ? $request->category_id : [$request->category_id];
    $query->whereHas('categories', function ($q) use ($categoryIds) {
        $q->whereIn('category_id', $categoryIds);
    });
}
```

## 4. Logic xem nhanh (Quick View)

**Câu hỏi:** Logic xem nhanh là gì?

**Trả lời:**
- **File controller:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 560-610)
- **Route:** `/client/san-pham/quickview/{slug}`

**Logic:**
1. **Backend:** Lấy thông tin sản phẩm với variants và attributes
2. **Frontend:** Sử dụng AJAX để gọi API và hiển thị modal
3. **File JS:** `DATN_QuaQue/public/frontend/assets/js/app.js` (dòng 8-34)
4. **File view:** `DATN_QuaQue/resources/views/frontend/wishlist/quickview.blade.php` (dòng 199-321)

**Cách hoạt động:**
- Click button "Xem nhanh" → Gọi AJAX đến `/client/san-pham/quickview/{slug}`
- Server trả về JSON với thông tin sản phẩm, variants, attributes
- JavaScript cập nhật nội dung modal và hiển thị
- Modal hiển thị: tên, giá, rating, mô tả, ảnh, biến thể

## 5. Logic thêm vào yêu thích

**Câu hỏi:** Logic thêm vào yêu thích?

**Trả lời:**
- **File controller:** `DATN_QuaQue/app/Http/Controllers/Client/WishlistController.php` (dòng 30-55)
- **File model:** `DATN_QuaQue/app/Models/Client/Wishlist.php`

**Logic:**
1. **Kiểm tra tồn tại:** Nếu đã có trong wishlist → xóa, nếu chưa có → thêm
2. **Toggle logic:**
```php
$exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();
if ($exists) {
    $exists->delete(); // Xóa khỏi wishlist
} else {
    Wishlist::create(['user_id' => $userId, 'product_id' => $productId]); // Thêm vào wishlist
}
```

3. **Frontend:** Sử dụng AJAX để gọi API không reload trang
4. **File JS:** `DATN_QuaQue/resources/views/frontend/home.blade.php` (dòng 1007-1056)
5. **UI:** Thay đổi class CSS để hiển thị trạng thái đã yêu thích (fill-heart)

## 6. Logic lọc của trang sản phẩm

**Câu hỏi:** Logic lọc của trang sản phẩm là gì?

**Trả lời:**
- **File:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 160-250)

**Các loại lọc:**

### Lọc theo danh mục (dòng 170-180):
```php
->when($request->dm, function ($q, $dm) {
    $ids = is_array($dm) ? $dm : explode(',', $dm);
    $q->whereHas('categories', fn($qq) => $qq->whereIn('categories.id', $ids), '=', count($ids));
})
```

### Lọc theo vùng miền (dòng 182-190):
```php
->when($request->regions, function ($q, $regions) {
    $ids = is_array($regions) ? $regions : explode(',', $regions);
    $q->whereIn('region_id', $ids);
})
```

### Lọc theo đánh giá (dòng 192-205):
```php
->when($request->rating, function ($q, $ratings) {
    $vals = is_array($ratings) ? $ratings : explode(',', $ratings);
    $q->whereRaw("(SELECT ROUND(AVG(r.rating)) FROM reviews r WHERE r.product_id = products.id) IN ($placeholders)", $vals);
})
```

### Lọc theo giá (dòng 207-230):
```php
->when($request->filled('min_price') || $request->filled('max_price'), function ($q) use ($request) {
    $q->whereHas('variants', function ($v) use ($min, $max) {
        $v->where('active', 1)
            ->when($min !== null, fn($vv) => $vv->where('price', '>=', (int) $min))
            ->when($max !== null, fn($vv) => $vv->where('price', '<=', (int) $max));
    });
})
```

### Sắp xếp (dòng 249-285):
- `low`: Giá thấp đến cao
- `high`: Giá cao đến thấp  
- `rating`: Đánh giá trung bình
- `aToz`: Tên A-Z
- `zToa`: Tên Z-A
- `pop`: Phổ biến nhất (mặc định)

## 7. Logic sản phẩm hết hàng đưa xuống cuối

**Câu hỏi:** Logic để chọn ra cái sản phẩm hết hàng đưa xuống cuối cùng và hiện ra chữ hết hàng đó như thế nào?

**Trả lời:**
- **File:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 232-248)

### Logic sắp xếp (dòng 232-248):
```php
->orderByRaw("
    CASE
        WHEN products.active = 1
             AND EXISTS (
                 SELECT 1 FROM product_variants pv
                 WHERE pv.product_id = products.id AND pv.active = 1 AND pv.stock > 0
             )
        THEN 0
        ELSE 1
    END
")
```
- Sản phẩm còn hàng: `CASE = 0` (ưu tiên cao)
- Sản phẩm hết hàng: `CASE = 1` (ưu tiên thấp)

### Logic kiểm tra stock (dòng 89-142):
```php
foreach ($allProducts as $product) {
    $hasStock = false;
    if ($product->has_variants) {
        foreach ($product->variants as $variant) {
            if ($variant->active == 1 && $variant->stock > 0) {
                $hasStock = true;
                break;
            }
        }
    } else {
        $defaultVariant = $product->variants->first();
        if ($defaultVariant && $defaultVariant->active == 1 && $defaultVariant->stock > 0) {
            $hasStock = true;
        }
    }
    if ($hasStock) {
        $inStockProducts->push($product);
    } else {
        $outOfStockProducts->push($product);
    }
}
$finalProducts = $inStockProducts->concat($outOfStockProducts);
```

### Hiển thị "Hết hàng" (dòng 504-510):
- **File:** `DATN_QuaQue/resources/views/frontend/products/catalog.blade.php`
```php
@if ($variantInStock && $product->active == 1)
    <h5 class="price"><span class="theme-color">{{ number_format($variantInStock->price, 0, ',', '.') }}₫</span></h5>
@else
    <h3 class="text-danger text-center">Hết hàng</h3>
@endif
```

### CSS cho sản phẩm hết hàng:
- **File:** `DATN_QuaQue/resources/views/frontend/products/partials/product-list.blade.php` (dòng 5)
```php
<div class="product-box-3 h-100 wow fadeInUp @if(!$product->variants->firstWhere(fn($v) => $v->stock > 0) || $product->active != 1) out-of-stock @endif">
```
