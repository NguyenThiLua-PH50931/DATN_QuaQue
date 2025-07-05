<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Attribute;
use App\Models\Client\CartItem;
use App\Models\Client\Product;
use App\Models\Client\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Lấy giỏ hàng của user (có eager load sản phẩm)
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        // Lấy dữ liệu biến thể (attributes + values) để hiển thị tên biến thể
        $attributes = Attribute::with('values')->get();

        $attributesData = [];
        foreach ($attributes as $attr) {
            $valuesArr = [];
            foreach ($attr->values as $value) {
                $valuesArr[$value->id] = $value->value;
            }
            $attributesData[$attr->id] = [
                'name' => $attr->name,
                'values' => $valuesArr,
            ];
        }
        //  dd($attributesData);

        // Trả về view giỏ hàng với dữ liệu
        return view('frontend.cart.cart', compact('cartItems', 'attributesData'));
    }

    public function add(Request $request)
    {
        $userId = Auth::id();

        $productId = $request->input('product_id');
        $quantityToAdd = (int) $request->input('quantity', 1);
        $variantAttributesRaw = $request->input('variant_attributes', null);

        $product = Product::with('variants.attributeValues')->find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
        }

        if ($variantAttributesRaw === '{}' || $variantAttributesRaw === '') {
            $variantAttributesRaw = null;
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_attributes' => 'nullable|string',
        ]);

        $arr = json_decode($variantAttributesRaw, true);
        if (!is_array($arr)) {
            $arr = [];
        }
        $arr = array_map('intval', $arr);
        ksort($arr);

        $variantAttributes = count($arr) > 0 ? json_encode($arr, JSON_UNESCAPED_UNICODE) : null;

        $variant = null;

        if ($variantAttributes) {
            $selectedAttrValueIds = array_values($arr);
            sort($selectedAttrValueIds);

            $variant = $product->variants->first(function ($v) use ($selectedAttrValueIds) {
                $attrValues = $v->attributeValues->pluck('id')->toArray();
                sort($attrValues);
                return $attrValues == $selectedAttrValueIds;
            });

            if (!$variant) {
                return redirect()->back()->with('error', 'Biến thể sản phẩm không hợp lệ hoặc không tồn tại.');
            }
        } else {
            // Trường hợp không có biến thể được chọn

            // Lấy biến thể đầu tiên nếu có
            $variant = $product->variants->first();

            if (!$variant) {
                // KHÔNG có biến thể nào => KHÔNG thể thêm (vì ko có price và stock)
                return redirect()->back()->with('error', 'Sản phẩm hiện không có biến thể để thêm vào giỏ hàng.');
            }
            Log::info('Variant mặc định được chọn:', ['variant_id' => $variant->id, 'stock' => $variant->stock, 'price' => $variant->price]);
        }

        $variantId = $variant->id;
        $price = $variant->price;
        $stock = $variant->stock ?? 0;

        if ($stock <= 0) {
            return redirect()->back()->with('error', 'Sản phẩm đã hết hàng, bạn không thể thêm vào giỏ.');
        }
        if ($price === null || $price <= 0) {
            return redirect()->back()->with('error', 'Giá sản phẩm không hợp lệ.');
        }

        // Tìm cart item với product + variant_attributes giống nhau
        // Tìm cart item với product + variant_attributes + variant_id giống nhau
        $cartQuery = CartItem::where('user_id', $userId)
            ->where('product_id', $productId);

        if ($variantAttributes === null) {
            $cartQuery->whereNull('variant_attributes');
        } else {
            $cartQuery->where('variant_attributes', $variantAttributes);
        }

        if ($variantId === null) {
            $cartQuery->whereNull('variant_id');
        } else {
            $cartQuery->where('variant_id', $variantId);
        }

        $existingCartItem = $cartQuery->first();



        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $quantityToAdd;

            if ($newQuantity > $stock) {
                return redirect()->back()->with('error', 'Số lượng sản phẩm vượt quá tồn kho. Tồn kho hiện tại: ' . $stock);
            }

            $existingCartItem->quantity = $newQuantity;
            $existingCartItem->price = $price;
            $existingCartItem->variant_id = $variantId;
            $existingCartItem->save();
        } else {
            if ($quantityToAdd > $stock) {
                return redirect()->back()->with('error', 'Số lượng sản phẩm vượt quá tồn kho. Tồn kho hiện tại: ' . $stock);
            }

            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantityToAdd,
                'variant_attributes' => $variantAttributes,
                'variant_id' => $variantId,
            ]);
        }

        return redirect()->route('client.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }



    public function delete($id)
    {
        $userId = Auth::id();
        $cartItem = CartItem::where('user_id', $userId)->find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
            ], 404);
        }

        try {
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi xóa sản phẩm.'
            ], 500);
        }
    }

    // Xử lý checkbox
    public function bulkDelete(Request $request)
    {
        $userId = Auth::id();

        $selectedIds = $request->input('selected_items', []);
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Bạn chưa chọn mục nào để xóa.');
        }

        CartItem::where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->delete();  // xóa mềm nếu model dùng SoftDeletes

        return redirect()->route('client.cart.index')->with('success', 'Đã xóa các mục đã chọn.');
    }

    // Update số lượng tại trang detail
    public function updateQuantity(Request $request)
    {
        $cartItemId = $request->cart_item_id;

        $cartItem = CartItem::with(['variant', 'product'])->find($cartItemId);
        Log::info('CartItem Debug:', [
            'cartItem' => $cartItem,
            'variant' => $cartItem->variant,
            'product' => $cartItem->product,
        ]);
        $action = $request->action;

        $cartItem = CartItem::with(['variant', 'product'])->find($cartItemId);
        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại'], 404);
        }

        $quantity = $cartItem->quantity;

        $stock = 0;

        if ($cartItem->variant && isset($cartItem->variant->stock)) {
            $stock = (int) $cartItem->variant->stock;
        } elseif ($cartItem->product && isset($cartItem->product->stock)) {
            $stock = (int) $cartItem->product->stock;
        }
        if ($action === 'increase') {
            if ($quantity + 1 <= $stock) {
                $quantity++;
            } else {
                return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho'], 400);
            }
        } elseif ($action === 'decrease' && $quantity > 1) {
            $quantity--;
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'quantity' => $quantity,
            'stock' => $stock,
        ]);
    }

    // Giỏ hàng ở header
    public function remove($id)
    {
        $userId = Auth::id();

        $cartItem = CartItem::where('user_id', $userId)->find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
            ], 404);
        }

        try {
            $cartItem->delete(); // Xóa mềm hoặc xóa luôn tùy bạn

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi xóa sản phẩm.'
            ], 500);
        }
    }

    // Cập nhập biến thể:
    public function updateVariant(Request $request)
    {
        $cartItemId = $request->cart_item_id;
        $variantId = $request->variant_id;

        $cartItem = CartItem::with('product', 'variant')->find($cartItemId);
        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm trong giỏ không tồn tại'], 404);
        }

        $newVariant = ProductVariant::find($variantId);
        if (!$newVariant || $newVariant->product_id != $cartItem->product_id) {
            return response()->json(['success' => false, 'message' => 'Biến thể không hợp lệ'], 400);
        }

        // Kiểm tra tồn kho
        if ($cartItem->quantity > $newVariant->stock) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho biến thể mới'], 400);
        }

        // Cập nhật cart item
        $cartItem->variant_id = $newVariant->id;
        $cartItem->price = $newVariant->price;
        // Nếu muốn lưu variant_attributes tương ứng thì thêm
        // $cartItem->variant_attributes = ... lấy thuộc tính của variant mới
        $cartItem->save();

        return response()->json([
            'success' => true,
            'newVariantName' => $newVariant->name,  // <-- Thêm dòng này
            'newPrice' => $newVariant->price,
            'quantity' => $cartItem->quantity,
            'stock' => $newVariant->stock,
        ]);
    }
public function proceedCheckout(Request $request)
{
    $selectedIds = $request->input('selected_items', []);

    // Đảm bảo $selectedIds là mảng số nguyên duy nhất
    if (!is_array($selectedIds)) {
        $selectedIds = [$selectedIds];
    }
    $selectedIds = array_unique(array_map('intval', $selectedIds));

    if (count($selectedIds) === 0) {
        return back()->with('error', 'Bạn chưa chọn sản phẩm nào để đặt hàng!');
    }

    // Chuyển thành chuỗi IDs ngăn cách bởi dấu phẩy
    $idsString = implode(',', $selectedIds);

    return redirect()->route('client.checkout', ['selected_cart_item_ids' => $idsString]);
}



}
