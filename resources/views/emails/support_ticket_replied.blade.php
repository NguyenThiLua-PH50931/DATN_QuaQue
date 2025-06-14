
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu hỗ trợ</title>
</head>
<body>
    <h1>Yêu cầu hỗ trợ #{{ $ticket->id }}</h1>
    <p>Xin chào {{ $ticket->user->name ?? $ticket->user->email }},</p>
    <p>Yêu cầu hỗ trợ của bạn với tiêu đề "<strong>{{ $ticket->title }}</strong>" đã được gửi thành công.</p>
    @if ($reply)
        <h3>Phản hồi từ admin:</h3>
        <p>{{ $reply }}</p>
    @endif
    <p><strong>Nội dung:</strong> {{ $ticket->content }}</p>
    <p><strong>Trạng thái:</strong> {{ $ticket->status == 'pending' ? 'Chờ xử lý' : 'Đã giải quyết' }}</p>
    <p><strong>Thời gian gửi:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
    <p>Chúng tôi sẽ phản hồi sớm nhất. Cảm ơn bạn!</p>
    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>

