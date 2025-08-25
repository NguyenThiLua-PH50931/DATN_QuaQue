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
    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        $userId = Auth::id();

        // Lấy danh sách item trong giỏ hàng của user
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        // Lấy toàn bộ thuộc tính và value của sản phẩm (phục vụ cho hiển thị)
        $attributes = Attribute::with('values')->get();
        $attributesData = [];
        foreach ($attributes as $attr) {
            $valuesArr = [];
            foreach ($attr->values as $value) {
                $valuesArr[$value->id] = $value->value;
            }
            $attributesData[$attr->id] = [
                'name'   => $attr->name,
                'values' => $valuesArr,
            ];
        }

        return view('frontend.cart.cart', compact('cartItems', 'attributesData'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request)
    {
        $userId = Auth::id();

        $productId = $request->input('product_id');
        $quantityToAdd = (int) $request->input('quantity', 1);
        $variantAttributesRaw = $request->input('variant_attributes', null);

        // Lấy sản phẩm + các biến thể
        $product = Product::with('variants.attributeValues')->find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
        }

        // Nếu không truyền biến thể (dạng object rỗng hoặc chuỗi rỗng) thì set null
        if ($variantAttributesRaw === '{}' || $variantAttributesRaw === '') {
            $variantAttributesRaw = null;
        }

        // Validate request đầu vào
        $request->validate([
            'product_id'        => 'required|exists:products,id',
            'quantity'          => 'required|integer|min:1',
            'variant_attributes' => 'nullable|string',
        ]);

        // Xử lý json biến thể
        $arr = json_decode($variantAttributesRaw, true);
        if (!is_array($arr)) $arr = [];
        $arr = array_map('intval', $arr);
        ksort($arr);

        $variantAttributes = count($arr) > 0 ? json_encode($arr, JSON_UNESCAPED_UNICODE) : null;
        $variant = null;

        // Nếu có chọn biến thể, tìm đúng variant đó
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
            // Không có biến thể, lấy variant đầu tiên nếu có
            $variant = $product->variants->first();
            if (!$variant) {
                return redirect()->back()->with('error', 'Sản phẩm hiện không có biến thể để thêm vào giỏ hàng.');
            }
        }

        $variantId = $variant->id;
        $price     = $variant->price;

        if ($price === null || $price <= 0) {
            return redirect()->back()->with('error', 'Giá sản phẩm không hợp lệ.');
        }

        // Tìm cart item hiện tại của user với sản phẩm + biến thể giống nhau
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
            // Cộng thêm vào giỏ (KHÔNG check tồn kho ở đây!)
            $existingCartItem->quantity += $quantityToAdd;
            $existingCartItem->price = $price;
            $existingCartItem->variant_id = $variantId;
            $existingCartItem->save();
        } else {
            // Tạo mới cart item
            CartItem::create([
                'user_id'            => $userId,
                'product_id'         => $productId,
                'price'              => $price,
                'quantity'           => $quantityToAdd,
                'variant_attributes' => $variantAttributes,
                'variant_id'         => $variantId,
            ]);
        }

        return redirect()->route('client.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ hàng
     */
    public function delete($id)
    {
        $userId = Auth::id();
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();


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

    /**
     * Xóa nhiều sản phẩm (checkbox chọn nhiều)
     */
    public function bulkDelete(Request $request)
    {
        $userId = Auth::id();
        $selectedIds = $request->input('selected_items', []);
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Bạn chưa chọn mục nào để xóa.');
        }

        CartItem::where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->delete();

        return redirect()->route('client.cart.index')->with('success', 'Đã xóa các mục đã chọn.');
    }

    /**
     * Update số lượng của sản phẩm trong giỏ hàng
     */
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

    /**
     * Xóa nhanh sản phẩm ở header giỏ hàng (AJAX)
     */
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

    /**
     * Đổi biến thể cho cart item
     */
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

        // Kiểm tra tồn kho của biến thể mới
        if ($cartItem->quantity > $newVariant->stock) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho loại mới'], 400);
        }

        // Cập nhật lại variant cho cart item
        $cartItem->variant_id = $newVariant->id;
        $cartItem->price = $newVariant->price;
        // Nếu muốn: $cartItem->variant_attributes = ...
        $cartItem->save();

        return response()->json([
            'success' => true,
            'newVariantName' => $newVariant->name,
            'newPrice' => $newVariant->price,
            'quantity' => $cartItem->quantity,
            'stock' => $newVariant->stock,
        ]);
    }

    /**
     * Đưa các sản phẩm đã chọn sang trang checkout
     */
    public function proceedCheckout(Request $request)
    {
        $selectedIds = $request->input('selected_cart_item_ids', []);

        // Đảm bảo $selectedIds là mảng số nguyên duy nhất
        if (!is_array($selectedIds)) {
            $selectedIds = [$selectedIds];
        }
        $selectedIds = array_unique(array_map('intval', $selectedIds));

        if (count($selectedIds) === 0) {
            return back()->with('error', 'Bạn chưa chọn sản phẩm nào để đặt hàng!');
        }

        // Xóa session liên quan đến giảm giá/shipping cũ
        session()->forget(['order_discount_code', 'free_shipping_code', 'shipping_method']);

        // Gửi sang trang checkout với danh sách cart_item_id
        $idsString = implode(',', $selectedIds);
        return redirect()->route('client.checkout', ['selected_cart_item_ids' => $idsString]);
    }

    /**
     * Kiểm tra tồn kho khi user nhấn Đặt hàng ở trang cart
     */
    public function checkStock(Request $request)
    {
        $cartItemIds = $request->input('selected_cart_item_ids', []);
        $messages = [];

        foreach ($cartItemIds as $id) {
            $item = \App\Models\Client\CartItem::with(['product', 'variant'])->find($id);
            if (!$item) continue;

            if ($item->variant_id) {
                $variant = $item->variant;
                if (!$variant || $variant->stock < $item->quantity) {
                    $messages[] = "Sản phẩm '{$item->product->name}' Loại '{$variant->name}' chỉ còn {$variant->stock} sản phẩm.";
                }
            } else {
                $product = $item->product;
                if (!$product || $product->stock < $item->quantity) {
                    $messages[] = "Sản phẩm '{$product->name}' chỉ còn {$product->stock} sản phẩm.";
                }
            }
        }

        return response()->json([
            'success' => count($messages) === 0,
            'messages' => $messages
        ]);
    }
    public function storeQuick(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Yêu cầu không hợp lệ.'], 400);
        }

        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'variant_id'  => 'required|exists:product_variants,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $userId    = Auth::id();
        $productId = $request->input('product_id');
        $variantId = $request->input('variant_id');
        $quantity  = (int) $request->input('quantity');

        // Lấy biến thể sản phẩm cùng thuộc tính của từng giá trị biến thể
        $variant = ProductVariant::with('attributeValues.attribute')->find($variantId);
        if (!$variant) {
            return response()->json(['message' => 'Biến thể không còn tồn tại.'], 404);
        }

        // Kiểm tra trạng thái và tồn kho
        if ($variant->stock <= 0 || !$variant->active) {
            return response()->json(['message' => 'Sản phẩm này hiện đã hết hàng.'], 400);
        }

        if ($quantity > $variant->stock) {
            return response()->json(['message' => 'Số lượng yêu cầu vượt quá tồn kho.'], 400);
        }

        $price = $variant->price;
        if ($price === null || $price <= 0) {
            return response()->json(['message' => 'Giá sản phẩm không hợp lệ.'], 400);
        }

        // --- CHUẨN HÓA variant_attributes dạng object ---
        $attributesArr = [];
        foreach ($variant->attributeValues as $attrValue) {
            // attribute_id lấy qua quan hệ attribute, hoặc có thể là $attrValue->attribute_id nếu đã có sẵn
            $attributeId = $attrValue->attribute_id ?? ($attrValue->attribute->id ?? null);
            if ($attributeId) {
                $attributesArr[$attributeId] = $attrValue->id;
            }
        }
        ksort($attributesArr); // Đảm bảo thứ tự key
        $variantAttributesJson = count($attributesArr) > 0 ? json_encode($attributesArr, JSON_UNESCAPED_UNICODE) : null;

        // --- Tìm cart item đã có ---
        $existingItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->where('variant_attributes', $variantAttributesJson)
            ->first();

        if ($existingItem) {
            // Cộng dồn số lượng, kiểm tra tồn kho
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity > $variant->stock) {
                return response()->json(['message' => 'Tổng số lượng trong giỏ vượt tồn kho.'], 400);
            }
            $existingItem->quantity = $newQuantity;
            $existingItem->price = $price;
            $existingItem->save();
        } else {
            // Thêm mới vào giỏ
            CartItem::create([
                'user_id'            => $userId,
                'product_id'         => $productId,
                'variant_id'         => $variantId,
                'variant_attributes' => $variantAttributesJson,
                'price'              => $price,
                'quantity'           => $quantity,
            ]);
        }

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng!'], 200);
    }
}
