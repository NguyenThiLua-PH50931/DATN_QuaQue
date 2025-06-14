<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo cập nhật trạng thái đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        <h2 style="color: #2d3748;">Xin chào {{ $order->user->name }},</h2>

        <p>Chúng tôi xin thông báo rằng đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được cập nhật trạng thái mới.</p>

        @php
            $statusTexts = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'processing' => 'Đang chuẩn bị hàng',
                'shipped' => 'Đã gửi hàng',
                'in_transit' => 'Đang vận chuyển',
                'delivered' => 'Đã giao hàng',
                'cancelled' => 'Đã hủy',
                'failed_delivery' => 'Giao hàng thất bại',
            ];
        @endphp

        <p><strong>Trạng thái mới:</strong>
            <span style="color: #1a73e8;">{{ $statusTexts[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}</span>
        </p>

        <p>Bạn có thể truy cập vào hệ thống để theo dõi đơn hàng và biết thêm chi tiết.</p>

        <div style="margin: 20px 0;">
            <a href="{{ route('admin.orders.tracking', $order->id) }}" style="display: inline-block; background-color: #1a73e8; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Xem trạng thái đơn hàng</a>
        </div>

        <hr>

        <p style="font-size: 14px; color: #666;">Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi để được hỗ trợ sớm nhất.</p>

        <p>Trân trọng,<br><strong>Đội ngũ Quà Quê</strong></p>
    </div>
</body>
</html>
