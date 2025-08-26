# CÂU HỎI THI FRONTEND - TRẢ LỜI CHI TIẾT

## 1. Logic đưa banner vào đúng chỗ ở trang chủ

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ClientHomeController.php` (dòng 18-60)

**Logic:**
- Sử dụng trường `location` trong bảng `banners` để xác định vị trí hiển thị
- Kiểm tra thời gian hiển thị: `display_at <= now <= display_end_at`
- Các vị trí banner được định nghĩa:
  - `main_hero_banner`: Banner chính lớn (8 cột)
  - `small_promo_banner_top`: Banner nhỏ phía trên (4 cột)
  - `small_promo_banner_bottom`: Banner nhỏ phía dưới (4 cột)
  - `slider_banner`: Banner slider (tối đa 4 banner)

**Code chính:**
```php
// Banner chính
$mainHeroBanner = Banner::where('location', 'main_hero_banner')
    ->where('active', true)
    ->where('display_at', '<=', $now)
    ->where('display_end_at', '>=', $now)
    ->first();

// Banner slider
$sliderBanners = Banner::where('location', 'slider_banner')
    ->where('active', true)
    ->orderBy('created_at', 'asc')
    ->take(4)
    ->get();
```

**View:** `DATN_QuaQue/resources/views/frontend/home.blade.php` (dòng 1-80)

---

## 2. Logic chọn sản phẩm cho các mục: Sản Phẩm Nổi Bật, Sản phẩm mới, Sản phẩm bán chạy

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ClientHomeController.php`

### Sản Phẩm Nổi Bật (dòng 62-95):
- **Logic:** Lấy sản phẩm có `view_total` cao nhất
- **Lọc:** Chỉ lấy sản phẩm còn hàng (stock > 0)
- **Số lượng:** Tối đa 8 sản phẩm

```php
$topViewedProducts = Product::with(['categories', 'variants'])
    ->withCount(['reviews as avg_rating' => function ($q) {
        $q->select(DB::raw('coalesce(avg(rating),0)'));
    }])
    ->where('active', 1)
    ->orderBy('view_total', 'desc')
    ->take(20)
    ->get();
```

### Sản Phẩm Mới (dòng 97-130):
- **Logic:** Lấy sản phẩm mới nhất theo `created_at`
- **Lọc:** Chỉ lấy sản phẩm còn hàng
- **Số lượng:** Tối đa 12 sản phẩm

```php
$latestProducts = Product::with(['categories', 'variants'])
    ->where('active', 1)
    ->latest()
    ->take(20)
    ->get();
```

### Sản Phẩm Bán Chạy (dòng 280-295):
- **Logic:** Tính tổng số lượng đã bán từ bảng `order_items`
- **Lọc:** Chỉ lấy sản phẩm active
- **Số lượng:** Tối đa 12 sản phẩm

```php
$bestSellingProducts = Product::with(['categories', 'variants'])
    ->select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
    ->join('order_items', 'order_items.product_id', '=', 'products.id')
    ->where('products.active', 1)
    ->groupBy('products.id')
    ->orderByDesc('total_sold')
    ->take(12)
    ->get();
```

---

## 3. Logic đưa danh mục ra và cách lọc từ trang chủ

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ClientHomeController.php` (dòng 132)

**Logic lấy danh mục:**
```php
$categories = Category::all();
```

**File:** `DATN_QuaQue/resources/views/frontend/home.blade.php` (dòng 80-120)

**Cách lọc từ trang chủ:**
- Sử dụng tham số `dm` (danh mục) trong URL
- Logic AND: Sản phẩm phải thuộc tất cả danh mục được chọn
- Chuyển hướng đến trang catalog với tham số filter

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 170-185)

```php
->when($request->dm, function ($q, $dm) {
    $ids = is_array($dm) ? $dm : explode(',', $dm);
    $ids = array_values(array_unique(array_map('intval', $ids)));
    if (!empty($ids)) {
        // đếm số category match phải = số category chọn
        $q->whereHas(
            'categories',
            fn($qq) => $qq->whereIn('categories.id', $ids),
            '=',
            count($ids)
        );
    }
})
```

---

## 4. Logic xem nhanh (Quick View)

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 558-650)

**Logic:**
1. **Route:** `/client/san-pham/quickview/{slug}`
2. **Controller:** Lấy thông tin sản phẩm với variants và attributes
3. **Frontend:** Modal popup hiển thị thông tin sản phẩm

**Code chính:**
```php
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
        ->firstOrFail();

    // Tạo variant map cho JavaScript
    $variantMap = $variants->map(function ($v) {
        return [
            'id' => (int) ($v->id),
            'stock' => (int) ($v->stock ?? 0),
            'price' => isset($v->price) ? (float) $v->price : null,
            'value_ids' => $v->attributeValues->pluck('id')->sort()->values()->all(),
        ];
    })->values()->all();

    return response()->json([
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'variants' => $variantMap,
            'attributes' => $attributeOptions,
        ]
    ]);
}
```

**Frontend:** `DATN_QuaQue/resources/views/frontend/wishlist/quickview.blade.php`

---

## 5. Logic thêm vào yêu thích (Wishlist)

**File:** `DATN_QuaQue/app/Http/Controllers/Client/WishlistController.php` (dòng 35-60)

**Logic:**
- **Toggle:** Nếu sản phẩm đã có trong wishlist thì xóa, nếu chưa có thì thêm
- **Validation:** Kiểm tra sản phẩm tồn tại
- **Response:** Trả về JSON với trạng thái toggle

```php
public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
    ]);
    
    $userId = Auth::id();
    $productId = $request->input('product_id');
    $exists = Wishlist::where('user_id', $userId)
        ->where('product_id', $productId)
        ->first();

    if ($exists) {
        $exists->delete();
        return response()->json([
            'success' => true,
            'toggled' => 'removed',
            'message' => 'Đã xóa khỏi wishlist!',
        ]);
    } else {
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
        return response()->json([
            'success' => true,
            'toggled' => 'added',
            'message' => 'Đã thêm vào wishlist!',
        ]);
    }
}
```

**Route:** `DATN_QuaQue/routes/web.php` (dòng 95-100)

---

## 6. Logic lọc của trang sản phẩm

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 160-300)

**Các loại filter:**

### a) Tìm kiếm theo tên (dòng 165-170):
```php
->when($request->q, fn($q, $kw) => 
    $q->where('products.name', 'like', "%{$kw}%")
)
```

### b) Filter theo danh mục (dòng 172-185):
```php
->when($request->dm, function ($q, $dm) {
    $ids = is_array($dm) ? $dm : explode(',', $dm);
    $q->whereHas('categories', fn($qq) => $qq->whereIn('categories.id', $ids), '=', count($ids));
})
```

### c) Filter theo vùng miền (dòng 187-195):
```php
->when($request->regions, function ($q, $regions) {
    $ids = is_array($regions) ? $regions : explode(',', $regions);
    $q->whereIn('region_id', $ids);
})
```

### d) Filter theo rating (dòng 197-210):
```php
->when($request->rating, function ($q, $ratings) {
    $vals = is_array($ratings) ? $ratings : explode(',', $ratings);
    $q->whereRaw("(SELECT ROUND(AVG(r.rating)) FROM reviews r WHERE r.product_id = products.id) IN ($placeholders)", $vals);
})
```

### e) Filter theo giá (dòng 212-230):
```php
->when($request->filled('min_price') || $request->filled('max_price'), function ($q) use ($request) {
    $min = $request->input('min_price');
    $max = $request->input('max_price');
    $q->whereHas('variants', function ($v) use ($min, $max) {
        $v->where('active', 1)
            ->when($min !== null, fn($vv) => $vv->where('price', '>=', (int) $min))
            ->when($max !== null, fn($vv) => $vv->where('price', '<=', (int) $max));
    });
})
```

### f) Sắp xếp (dòng 250-290):
```php
->when($request->sort, function ($q, $sort) {
    switch ($sort) {
        case 'low': // giá thấp nhất
        case 'high': // giá cao nhất
        case 'rating': // rating cao nhất
        case 'aToz': // tên A-Z
        case 'zToa': // tên Z-A
        default: // phổ biến (view_total)
    }
})
```

---

## 7. Logic sản phẩm hết hàng đưa xuống cuối và hiển thị chữ "Hết hàng"

**File:** `DATN_QuaQue/app/Http/Controllers/Client/ProductController.php` (dòng 232-250)

**Logic ưu tiên còn hàng trước:**
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

**Logic kiểm tra stock (dòng 300-340):**
```php
$products->getCollection()->transform(function ($product) {
    $hasStock = false;
    $totalStock = 0;

    if ($product->variants->count() > 0) {
        foreach ($product->variants as $variant) {
            if ($variant->active == 1) {
                $totalStock += $variant->stock;
                if ($variant->stock > 0) {
                    $hasStock = true;
                }
            }
        }
    }

    $product->has_stock = $hasStock;
    $product->total_stock = $totalStock;

    return $product;
});
```

**Frontend hiển thị:** `DATN_QuaQue/resources/views/frontend/products/partials/product-list.blade.php`

```blade
@if(!$product->has_stock)
    <div class="out-of-stock-badge">
        <span class="badge bg-danger">Hết hàng</span>
    </div>
@endif
```

**Logic phân loại còn hàng/hết hàng (dòng 100-150):**
```php
// Phân loại sản phẩm còn hàng và hết hàng
$inStockProducts = collect();
$outOfStockProducts = collect();

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

// Gộp lại: còn hàng trước, hết hàng sau
$finalProducts = $inStockProducts->concat($outOfStockProducts);
```

---

## TÓM TẮT CÁC FILE CHÍNH:

1. **ClientHomeController.php:** Logic trang chủ, banner, sản phẩm nổi bật/mới/bán chạy
2. **ProductController.php:** Logic lọc sản phẩm, quick view, sắp xếp
3. **WishlistController.php:** Logic thêm/xóa yêu thích
4. **home.blade.php:** View trang chủ
5. **catalog.blade.php:** View trang sản phẩm
6. **product-list.blade.php:** Partial view danh sách sản phẩm
7. **quickview.blade.php:** Modal xem nhanh sản phẩm

---

## CÁC SỬA ĐỔI ĐÃ THỰC HIỆN:

### 1. Sửa lỗi sản phẩm hết hàng không thể xem nhanh/xem chi tiết:
- **File:** `catalog.blade.php`, `index.blade.php`, `product-list.blade.php`
- **Thay đổi:** Giữ nguyên các link xem nhanh và xem chi tiết, chỉ thay đổi hiển thị giá
- **Logic:** Sản phẩm hết hàng vẫn có thể xem nhanh và xem chi tiết, chỉ không hiển thị giá

### 2. Sửa lỗi hiển thị "Hết hàng" không ổn định:
- **File:** `ProductController.php` (dòng 320-340)
- **Thay đổi:** Thêm thuộc tính `is_out_of_stock` cho mỗi sản phẩm
- **Logic:** Kiểm tra stock > 0 và active = 1 để xác định trạng thái hết hàng

### 3. Cải thiện giao diện sản phẩm hết hàng:
- **File:** `catalog.blade.php`, `index.blade.php`
- **Thay đổi:** 
  - Thêm badge "Hết hàng" trên ảnh sản phẩm
  - Thêm overlay mờ cho ảnh sản phẩm hết hàng
  - Thêm class CSS `out-of-stock` cho styling
  - Hiển thị "Hết hàng" thay vì giá khi sản phẩm hết hàng

### 4. Logic kiểm tra stock được cải thiện:
```php
// Trước
$variantInStock = $product->variants->firstWhere(fn($v) => $v->active == 1);

// Sau  
$variantInStock = $product->variants->firstWhere(fn($v) => $v->active == 1 && $v->stock > 0);
$isOutOfStock = !$variantInStock || $product->active != 1;
```

### 5. CSS cho sản phẩm hết hàng:
```css
/* Badge hết hàng */
.out-of-stock-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 10;
}

/* Overlay mờ */
.product-box-3.out-of-stock .product-image::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 16px;
    z-index: 5;
}

/* Ảnh mờ */
.product-box-3.out-of-stock .product-image > a > img {
    filter: grayscale(30%);
}
```

### 6. Sửa lỗi "Undefined variable $isOutOfStock":
- **File:** `catalog.blade.php`, `product-list.blade.php`
- **Vấn đề:** Biến `$isOutOfStock` được sử dụng nhưng chưa được định nghĩa
- **Giải pháp:** Thêm logic định nghĩa biến ở đầu vòng lặp `@forelse`
- **Code sửa:**
```php
@forelse ($products as $product)
    @php
        // Kiểm tra sản phẩm có còn hàng không
        $variantInStock = $product->variants->firstWhere(
            fn($v) => $v->active == 1 && $v->stock > 0,
        );
        $isOutOfStock = !$variantInStock || $product->active != 1;
    @endphp
    // ... rest of the code
@endforelse
```

'### 7. Sửa lỗi không thể xem nhanh/xem chi tiết sản phẩm hết hàng:
- **File:** `ProductController.php` (dòng 565)
- **Vấn đề:** Method `quickView()` có điều kiện `->where('active', 1)` chặn sản phẩm không active
- **Giải pháp:** Comment điều kiện này để cho phép xem nhanh cả sản phẩm hết hàng
- **Code sửa:**
```php
public function quickView($slug)
{
    $product = Product::with([
        'images',
        'category',
        'variants.attributeValues.attribute',
        'reviews'
    ])
        ->where('slug', $slug)
        // ->where('active', 1) // Cho phép xem nhanh cả sản phẩm hết hàng
        ->whereNull('deleted_at')
        ->firstOrFail();
}
```

### 8. Cải thiện CSS cho button xem nhanh/xem chi tiết:
- **File:** `catalog.blade.php`, `index.blade.php`, `product-list.blade.php`
- **Vấn đề:** Overlay mờ có thể che phủ các button
- **Giải pháp:** Thêm CSS để đảm bảo các button vẫn có thể click được
- **Code sửa:**
```css
/* Đảm bảo các button vẫn có thể click được */
.product-box-3.out-of-stock .product-option {
    z-index: 20;
    position: relative;
}

.product-box-3.out-of-stock .product-option a {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.product-box-3.out-of-stock .product-option a:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
```

### 9. Sửa lỗi overlay che phủ button xem nhanh:
- **File:** `catalog.blade.php`, `product-list.blade.php`
- **Vấn đề:** Lớp overlay đen (`::after`) che phủ hoàn toàn các button xem nhanh
- **Giải pháp:** Di chuyển overlay vào trong thẻ `<a>` để chỉ che ảnh, không che button
- **Code sửa:**
```css
/* Trước: overlay che toàn bộ product-image */
.product-box-3.out-of-stock .product-image::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 16px;
    z-index: 5;
}

/* Sau: overlay chỉ che ảnh bên trong thẻ <a> */
.product-box-3.out-of-stock .product-image > a::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 12px;
    z-index: 1;
}
```

### 10. Sửa lỗi quickview không hoạt động sau khi AJAX (trang 2, 3...):
- **File:** `catalog.blade.php`
- **Vấn đề:** Sau khi AJAX load trang mới, events cho button quickview bị mất
- **Giải pháp:** Thêm function re-bind events sau khi AJAX
- **Code sửa:**
```javascript
// Trong function fetchProducts()
// Re-bind quickview events sau khi AJAX
bindQuickviewEventsAfterAjax();

// Re-bind wishlist events sau khi AJAX  
bindWishlistEventsAfterAjax();

// Function để bind events
function bindQuickviewEventsAfterAjax() {
    document.querySelectorAll('.quickview-btn').forEach(function(btn) {
        btn.removeEventListener('click', handleQuickviewClick);
        btn.addEventListener('click', handleQuickviewClick);
    });
}

function bindWishlistEventsAfterAjax() {
    document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
        btn.removeEventListener('click', handleWishlistClick);
        btn.addEventListener('click', handleWishlistClick);
    });
}
```

### 11. Sửa lỗi mất CSS khi quay lại từ trang chi tiết (trang 2, 3...):
- **File:** `catalog.blade.php`
- **Vấn đề:** Khi click vào sản phẩm ở trang 2 → xem chi tiết → quay lại → mất CSS và hiển thị sai
- **Nguyên nhân:** URL mất tham số `page` và các filter khác khi quay lại
- **Giải pháp:** Lưu và khôi phục trạng thái trang bằng sessionStorage
- **Code sửa:**
```javascript
// Lưu trạng thái hiện tại vào sessionStorage
function saveCurrentState() {
    const currentParams = new URLSearchParams(window.location.search);
    const state = {
        page: currentParams.get('page') || '1',
        q: currentParams.get('q') || '',
        dm: currentParams.get('dm') || '',
        regions: currentParams.get('regions') || '',
        rating: currentParams.get('rating') || '',
        min_price: currentParams.get('min_price') || '',
        max_price: currentParams.get('max_price') || '',
        sort: currentParams.get('sort') || ''
    };
    sessionStorage.setItem('catalog_state', JSON.stringify(state));
}

// Khôi phục trạng thái khi quay lại
function restoreCurrentState() {
    const savedState = sessionStorage.getItem('catalog_state');
    if (!savedState) return; // Không có dữ liệu thì bỏ qua

    try {
        const state = JSON.parse(savedState);
        const currentParams = new URLSearchParams(window.location.search);
        let hasChanges = false;

        // Chỉ khôi phục nếu đang ở trang catalog và không có tham số nào
        if (window.location.pathname.includes('/client/san-pham') && currentParams.toString() === '') {
            // Khôi phục các tham số đã lưu
            Object.keys(state).forEach(key => {
                if (state[key] && state[key] !== '') {
                    currentParams.set(key, state[key]);
                    hasChanges = true;
                }
            });

            if (hasChanges) {
                const newUrl = window.location.pathname + '?' + currentParams.toString();
                history.replaceState({}, '', newUrl);
                // Delay một chút để đảm bảo trang đã load xong
                setTimeout(() => {
                    fetchProducts(currentParams);
                }, 100);
            }
        }
    } catch (error) {
        console.error('Lỗi khôi phục trạng thái:', error);
        // Xóa dữ liệu lỗi
        sessionStorage.removeItem('catalog_state');
    }
}
```

### 12. Sửa lỗi lần đầu load trang bị lỗi:
- **File:** `catalog.blade.php`
- **Vấn đề:** Lần đầu chạy web → xem chi tiết sản phẩm → quay lại → bị lỗi CSS, F5 thì bình thường
- **Nguyên nhân:** 
  - Lần đầu sessionStorage chưa có dữ liệu
  - Logic khôi phục được gọi quá sớm
  - Không kiểm tra điều kiện trước khi khôi phục
- **Giải pháp:** Cải thiện logic khôi phục và timing
- **Code sửa:**
```javascript
// Khôi phục trạng thái khi load trang (chỉ khi quay lại từ trang khác)
let isFirstLoad = true;
document.addEventListener('DOMContentLoaded', function() {
    if (isFirstLoad) {
        isFirstLoad = false;
        
        // Luôn lưu trạng thái hiện tại (dù có tham số hay không)
        saveCurrentState();
        
        // Chỉ khôi phục nếu có referrer (quay lại từ trang khác)
        if (document.referrer && document.referrer.includes('/client/san-pham/')) {
            setTimeout(restoreCurrentState, 200);
        }
    }
});
```

### 13. Sửa lỗi trang 2 vẫn bị lỗi khi quay lại:
- **File:** `catalog.blade.php`
- **Vấn đề:** Trang 1 đã ổn nhưng trang 2 vẫn bị lỗi khi quay lại từ trang chi tiết
- **Nguyên nhân:** 
  - Logic khôi phục chỉ hoạt động khi URL không có tham số nào
  - Trang 2 đã có tham số `page=2` nên không khôi phục được
  - Không lưu trạng thái khi click vào sản phẩm
- **Giải pháp:** Cải thiện logic khôi phục và lưu trạng thái
- **Code sửa:**
```javascript
// Khôi phục trạng thái khi quay lại
function restoreCurrentState() {
    const savedState = sessionStorage.getItem('catalog_state');
    if (!savedState) return; // Không có dữ liệu thì bỏ qua

    try {
        const state = JSON.parse(savedState);
        const currentParams = new URLSearchParams(window.location.search);
        let hasChanges = false;

        // Chỉ khôi phục nếu đang ở trang catalog
        if (window.location.pathname.includes('/client/san-pham')) {
            // So sánh trạng thái hiện tại với trạng thái đã lưu
            let needRestore = false;
            
            // Kiểm tra xem có cần khôi phục không
            Object.keys(state).forEach(key => {
                const savedValue = state[key] || '';
                const currentValue = currentParams.get(key) || '';
                
                // Nếu giá trị khác nhau và giá trị đã lưu không rỗng
                if (savedValue !== currentValue && savedValue !== '') {
                    needRestore = true;
                }
            });
            
            // Nếu cần khôi phục
            if (needRestore) {
                // Khôi phục các tham số đã lưu
                Object.keys(state).forEach(key => {
                    if (state[key] && state[key] !== '') {
                        currentParams.set(key, state[key]);
                        hasChanges = true;
                    }
                });

                if (hasChanges) {
                    const newUrl = window.location.pathname + '?' + currentParams.toString();
                    history.replaceState({}, '', newUrl);
                    // Delay một chút để đảm bảo trang đã load xong
                    setTimeout(() => {
                        fetchProducts(currentParams);
                    }, 100);
                }
            }
        }
    } catch (error) {
        console.error('Lỗi khôi phục trạng thái:', error);
        // Xóa dữ liệu lỗi
        sessionStorage.removeItem('catalog_state');
    }
}

// Lưu trạng thái hiện tại khi chuyển trang hoặc click vào sản phẩm
document.addEventListener('click', function(e) {
    const a = e.target.closest('.custome-pagination a');
    const productLink = e.target.closest('.product-image a');
    
    if (a) {
        setTimeout(saveCurrentState, 100); // Lưu sau khi URL đã thay đổi
    }
    
    if (productLink) {
        // Lưu trạng thái ngay khi click vào sản phẩm
        saveCurrentState();
    }
});
```
