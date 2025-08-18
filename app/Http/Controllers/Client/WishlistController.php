<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Wishlist;
use App\Models\Client\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{


    /**
     * Hiển thị danh sách wishlist
     */
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with(['product' => function ($query) {
                $query->where('active', true)
                    ->with(['categories', 'variants']); // Lấy nhiều danh mục!
            }])
            ->paginate(12);

        return view('frontend.wishlist.index', compact('wishlist'));
    }

    /**
     * Thêm sản phẩm vào wishlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Sản phẩm đã có trong wishlist!');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Đã thêm vào wishlist!');
    }

    /**
     * Xóa sản phẩm khỏi wishlist
     */
    public function destroy($product_id)
    {
        $deleted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        if (request()->ajax()) {
            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Đã xóa khỏi wishlist!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong wishlist!'], 404);
            }
        }
        return redirect()->back()->with('success', 'Đã xóa khỏi wishlist!');
    }
}
