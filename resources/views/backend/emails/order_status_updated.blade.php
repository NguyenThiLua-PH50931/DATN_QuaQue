<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật trạng thái đơn hàng</title>
</head>

<body style="margin:0; padding:0; background:#f4f4f4; font-family:Arial,sans-serif; color:#222;">
    <div
        style="max-width:600px; margin:36px auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 14px #0000000d;">
        <div style="background:#1a73e8; padding:24px 24px 14px 24px;">
            <h2 style="color:#fff; font-size:1.3rem; margin:0; font-weight:600; letter-spacing:1px;">
                <span style="font-size:1.5rem; vertical-align:-3px;">🎁</span> Quà Quê - Cập nhật đơn hàng
            </h2>
        </div>

        <div style="padding:30px 28px 20px 28px;">
            <p style="font-size:1.09rem; margin-bottom:14px; margin-top:0;">
                Xin chào <strong>{{ $order->user->name }}</strong>,
            </p>

            <p style="margin:0 0 18px 0;">
                Đơn hàng <strong style="color:#1a73e8;">#{{ $order->order_code }}</strong> của bạn vừa được cập nhật
                trạng thái:
            </p>

            @php
                $statusTexts = [
                    'pending' => ['⏳', 'Chờ xác nhận', '#ff9800'],
                    'confirmed' => ['✅', 'Đã xác nhận', '#2196f3'],
                    'processing' => ['📦', 'Đang chuẩn bị hàng', '#ff9800'],
                    'shipped' => ['🚚', 'Đã gửi hàng', '#673ab7'],
                    'in_transit' => ['🚛', 'Đang vận chuyển', '#2196f3'],
                    'delivered' => ['📬', 'Đã giao hàng', '#4caf50'],
                    'cancelled' => ['❌', 'Đã hủy', '#e53935'],
                    'failed_delivery' => ['⚠️', 'Giao hàng thất bại', '#757575'],
                ];
                $status = $statusTexts[$order->status] ?? ['', '', $order->status];
            @endphp

            <div style="margin:28px 0 26px 0; text-align:center;">
                <span style="font-size:2.3rem; vertical-align:middle; margin-right:6px;">{!! $status[0] !!}</span>
                <span style="font-size:1.35rem; color:{{ $status[2] }}; font-weight:600; vertical-align:middle;">
                    {!! $status[1] !!}
                </span>
            </div>

            {{-- *** NHẤN MẠNH LÝ DO HỦY *** --}}
            @if ($order->status === 'cancelled' && !empty($order->cancel_reason))
                <div
                    style="background:#fff0f1; border-left:5px solid #e53935; padding:20px 18px 15px 18px; border-radius:6px; margin-bottom:30px;">
                    <div style="font-size:1.07rem; font-weight:600; color:#e53935; margin-bottom:8px;">
                        Lý do hủy đơn hàng:
                    </div>
                    <div style="font-size:1rem; color:#b71c1c; line-height:1.7;">
                        {{ $order->cancel_reason }}
                    </div>
                </div>
            @endif

            <p style="margin:0 0 20px 0;">
                Để xem chi tiết đơn hàng, bạn hãy nhấn nút bên dưới:
            </p>

            <div style="text-align:center; margin:26px 0;">
                <a href="{{ route('client.orders.show', ['order' => $order->id]) }}"
                    style="background:#1a73e8; color:#fff; text-decoration:none; padding:14px 38px; border-radius:6px; font-size:1.08rem; font-weight:500; display:inline-block; box-shadow:0 2px 8px #4b92e134;">
                    🔎 Xem đơn hàng
                </a>

            </div>


            <hr style="border:none; border-top:1px solid #eee; margin:32px 0 24px 0;">

            <p style="font-size:0.98rem; color:#444;">
                Nếu bạn cần hỗ trợ, vui lòng phản hồi lại email này hoặc liên hệ với bộ phận CSKH của chúng tôi.<br>
            </p>
            <p style="margin-top:22px; margin-bottom:0;">
                Trân trọng,<br>
                <strong style="color:#1a73e8;">Đội ngũ Quà Quê</strong>
            </p>
        </div>
        <div style="background:#f0f2f6; text-align:center; padding:12px 0; font-size:12px; color:#888;">
            © {{ date('Y') }} Quà Quê. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>

</html>
