<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật trạng thái đơn hàng</title>
</head>

<body style="margin:0; padding:0; background:#f5f6fa; font-family:Arial, sans-serif; color:#333;">
    <div
        style="max-width:640px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 3px 12px rgba(0,0,0,0.08);">

        <!-- Header -->
        <div style="background:#1a73e8; padding:20px 24px;">
            <h2 style="color:#fff; font-size:18px; margin:0; font-weight:600;">
                Quà Quê - Thông báo đơn hàng
            </h2>
        </div>

        <!-- Body -->
        <div style="padding:28px;">
            <p style="font-size:15px; margin:0 0 14px 0;">
                Kính gửi quý khách <strong>{{ $order->user->name }}</strong>,
            </p>

            <p style="font-size:15px; line-height:1.6; margin:0 0 18px 0;">
                Đơn hàng <strong style="color:#1a73e8;">#{{ $order->order_code }}</strong> của bạn vừa được cập nhật
                trạng thái:
            </p>

            @php
                $statusTexts = [
                    'pending' => ['Chờ xác nhận', '#ff9800'],
                    'confirmed' => ['Đã xác nhận', '#2196f3'],
                    'processing' => ['Đang chuẩn bị hàng', '#ff9800'],
                    'shipped' => ['Đã gửi hàng', '#673ab7'],
                    'in_transit' => ['Đang vận chuyển', '#2196f3'],
                    'delivered' => ['Đã giao hàng', '#4caf50'],
                    'cancelled' => ['Đã hủy', '#e53935'],
                    'failed_delivery' => ['Giao hàng thất bại', '#757575'],
                ];
                $status = $statusTexts[$order->status] ?? [$order->status, '#333'];
            @endphp

            <!-- Trạng thái -->
            <div
                style="margin:20px 0; padding:14px 16px; background:#f9f9f9; border-left:4px solid {{ $status[1] }}; border-radius:4px;">
                <span style="font-size:15px; font-weight:600; color:{{ $status[1] }};">{{ $status[0] }}</span>
            </div>

            <!-- Nếu đơn bị hủy -->
            @if ($order->status === 'cancelled')
                <div
                    style="background:#fff4f4; border:1px solid #f3c0c0; padding:16px; border-radius:6px; margin-bottom:20px;">

                    @if (!empty($order->cancel_reason))
                        <div style="font-size:14px; font-weight:600; color:#d32f2f; margin-bottom:6px;">
                            Lý do hủy đơn hàng:
                        </div>
                        <div style="font-size:14px; color:#444; line-height:1.6; margin-bottom:12px;">
                            {{ $order->cancel_reason }}
                        </div>
                    @endif

                    {{-- ✨ Nếu thanh toán online thì thêm thông tin hoàn tiền --}}
                    @php
                        $onlineMethods = ['momo', 'zalopay', 'vnpay'];
                    @endphp
                    @if (in_array($order->payment_method, $onlineMethods))
                        <div style="font-size:14px; font-weight:600; color:#1a73e8; margin-bottom:6px;">
                            Đơn hàng đã được hoàn tiền
                        </div>
                        <p style="font-size:14px; margin:0; color:#333; line-height:1.6;">
                            Số tiền hoàn lại:
                            <strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong><br>
                            Giao dịch được hoàn qua phương thức thanh toán ban đầu
                            ({{ strtoupper($order->payment_method) }}).
                        </p>
                    @endif
                </div>
            @endif

            <!-- Nút xem chi tiết -->
            <div style="text-align:center; margin:28px 0;">
                <a href="{{ route('client.orders.show', ['order' => $order->id]) }}"
                    style="background:#1a73e8; color:#fff; text-decoration:none; padding:12px 28px; border-radius:4px; font-size:14px; font-weight:500; display:inline-block;">
                    Xem chi tiết đơn hàng
                </a>
            </div>

            <!-- Hỗ trợ -->
            <p style="font-size:14px; color:#444; line-height:1.6; margin:0 0 20px 0;">
                Nếu quý khách cần hỗ trợ thêm, vui lòng phản hồi trực tiếp email này hoặc liên hệ hotline
                <strong style="color:#1a73e8;">012345678</strong>.
            </p>

            <p style="font-size:13px; color:#777; line-height:1.6; margin:0;">
                Xin cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của <strong>Quà Quê</strong>.
            </p>

            <p style="margin-top:20px; font-size:14px; color:#333;">
                Trân trọng,<br>
                <strong style="color:#1a73e8;">Đội ngũ Quà Quê</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#f1f1f1; text-align:center; padding:12px 0; font-size:12px; color:#888;">
            © {{ date('Y') }} Quà Quê. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>

</html>
