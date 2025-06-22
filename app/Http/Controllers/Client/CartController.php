<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
   public function index(){
        // Lấy giỏ hàng hiện tại của user, kèm sản phẩm
        $userId = Auth::id();

        $cart = DB::table('carts')->where('user_id', $userId)->first();

        if(!$cart) {
            // Tạo giỏ hàng mới nếu chưa có
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $cartId = $cart->id;
        }

        // Lấy danh sách sản phẩm trong giỏ hàng
        $cartItems = DB::table('cart_items')->where('user_id', $userId)->get();

        return view('frontend.cart.cart', compact('cartItems'));
    }

    public function add(Request $request)
    {
        //  dd($request->all());
        $userId = Auth::id();

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $price = $request->input('price');

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $existing = DB::table('cart_items')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            // Cập nhật số lượng
            DB::table('cart_items')
                ->where('id', $existing->id)
                ->update([
                    'quantity' => $existing->quantity + $quantity,
                    'updated_at' => now()
                ]);
        } else {
            // Thêm sản phẩm mới vào giỏ
            DB::table('cart_items')->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('client.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }
}
