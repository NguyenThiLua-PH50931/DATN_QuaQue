<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Order;
use App\Models\Client\OrderItem;
use App\Models\Client\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'order_id'                 => 'required|exists:orders,id',
            'order_item_id'            => 'required|exists:order_items,id',
            'product_id'               => 'required|exists:products,id',
            'product_variant_value_id' => 'required|exists:product_variants,id', // <-- validate chuẩn bảng product_variants
            'rating'                   => 'required|integer|min:1|max:5',
            'content'                  => 'required|string|max:250',
        ], [
            'order_id.required'                 => 'Thiếu đơn hàng.',
            'order_id.exists'                   => 'Đơn hàng không tồn tại.',
            'order_item_id.required'            => 'Thiếu sản phẩm trong đơn.',
            'order_item_id.exists'              => 'Sản phẩm trong đơn không tồn tại.',
            'product_id.required'               => 'Thiếu mã sản phẩm.',
            'product_id.exists'                 => 'Sản phẩm không tồn tại.',
            'product_variant_value_id.required' => 'Thiếu biến thể sản phẩm.',
            'product_variant_value_id.exists'   => 'Biến thể sản phẩm không hợp lệ.',
            'rating.required'                   => 'Vui lòng chọn số sao.',
            'rating.integer'                    => 'Số sao phải là số nguyên.',
            'rating.min'                        => 'Số sao thấp nhất là 1.',
            'rating.max'                        => 'Số sao cao nhất là 5.',
            'content.required'                  => 'Vui lòng nhập bình luận.',
            'content.string'                    => 'Bình luận phải là dạng văn bản.',
            'content.max'                       => 'Bình luận tối đa 250 ký tự.',
        ]);

        // Kiểm tra đăng nhập
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Bạn cần đăng nhập để đánh giá.'
            ], 401);
        }

        // Lấy order_item, kiểm tra quyền sở hữu và đúng biến thể, sản phẩm
        $orderItem = OrderItem::with('order')->where('id', $request->order_item_id)->first();
        if (
            !$orderItem
            || $orderItem->order->user_id != $user->id
            || $orderItem->product_id != $request->product_id
            || $orderItem->product_variant_value_id != $request->product_variant_value_id // So sánh đúng giá trị của order_items và request
        ) {
            return response()->json([
                'message' => 'Bạn không có quyền đánh giá cho sản phẩm/biến thể này trong đơn hàng này.'
            ], 403);
        }

        // Check đã review chưa
        $exists = Review::where('user_id', $user->id)
            ->where('order_id', $orderItem->order_id)
            ->where('order_item_id', $orderItem->id)
            ->where('product_id', $orderItem->product_id)
            ->where('product_variant_value_id', $orderItem->product_variant_value_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi!'
            ], 409);
        }

        // Lưu đánh giá mới
        $review = Review::create([
            'user_id'                 => $user->id,
            'order_id'                => $orderItem->order_id,
            'order_item_id'           => $orderItem->id,
            'product_id'              => $orderItem->product_id,
            'product_variant_value_id' => $orderItem->product_variant_value_id,
            'rating'                  => $request->rating,
            'content'                 => $request->content,
        ]);

        // Đánh dấu order_item đã được review
        $orderItem->is_reviewed = 1;
        $orderItem->save();

        return response()->json([
            'message' => 'Đánh giá của bạn đã được ghi nhận. Xin cảm ơn!',
            'review'  => $review
        ], 201);
    }
}
