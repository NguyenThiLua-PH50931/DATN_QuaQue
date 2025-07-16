<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo cập nhật trạng thái đơn hàng</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f4f4f4; color: #333;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <div style="background-color: #1a73e8; padding: 20px;">
            <h2 style="color: #fff; margin: 0;">🎁 Quà Quê - Cập nhật đơn hàng</h2>
        </div>

        <div style="padding: 30px;">
            <p style="font-size: 16px; margin-bottom: 20px;">Xin chào <strong>{{ $order->user->name }}</strong>,</p>

            <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được cập nhật trạng thái mới:</p>

            @php
                $statusTexts = [
                    'pending' => '⏳ Chờ xác nhận',
                    'confirmed' => '✅ Đã xác nhận',
                    'processing' => '📦 Đang chuẩn bị hàng',
                    'shipped' => '🚚 Đã gửi hàng',
                    'in_transit' => '🚛 Đang vận chuyển',
                    'delivered' => '📬 Đã giao hàng',
                    'cancelled' => '❌ Đã hủy',
                    'failed_delivery' => '⚠️ Giao hàng thất bại',
                ];
            @endphp

            <p style="font-size: 18px; margin: 20px 0; color: #1a73e8;">
                <strong>{{ $statusTexts[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}</strong>
            </p>

            <p>Để xem chi tiết đơn hàng, bạn có thể truy cập hệ thống theo nút bên dưới:</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/order.index') }}" style="background-color: #1a73e8; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 4px; display: inline-block; font-size: 16px;">🔎 Xem đơn hàng</a>
            </div>

            <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

            <p style="font-size: 14px; color: #666;">Nếu bạn cần hỗ trợ, vui lòng phản hồi lại email này hoặc liên hệ với bộ phận CSKH của chúng tôi.</p>

            <p style="margin-top: 30px;">Trân trọng,<br><strong>Đội ngũ Quà Quê</strong></p>
        </div>

        <div style="background-color: #f0f0f0; text-align: center; padding: 10px; font-size: 12px; color: #999;">
            © {{ date('Y') }} Quà Quê. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>
</html>
