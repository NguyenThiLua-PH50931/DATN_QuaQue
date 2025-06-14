<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Phản hồi yêu cầu hỗ trợ</title>
</head>
<body>
    <h1>Phản hồi yêu cầu hỗ trợ #{{ $ticket->id }}</h1>
    <p>Xin chào {{ $ticket->user->name ?? $ticket->user->email }},</p>
    <p>Chúng tôi đã phản hồi yêu cầu hỗ trợ của bạn với tiêu đề: <strong>{{ $ticket->title }}</strong>.</p>
    <h3>Phản hồi:</h3>
    <p>{{ $reply }}</p>
    <h3>Thông tin yêu cầu:</h3>
    <p><strong>Nội dung:</strong> {{ $ticket->content }}</p>
    <p><strong>Trạng thái:</strong> {{ $ticket->status == 'resolved' ? 'Đã giải quyết' : 'Chờ xử lý' }}</p>
    <p><strong>Thời gian gửi:</strong> {{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : 'Chưa xác định' }}</p>
    <p>Cảm ơn bạn đã liên hệ với chúng tôi!</p>
    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
