<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Phản hồi bình luận</title>
</head>
<body>
    <h1>Phản hồi bình luận #{{ $comment->id }}</h1>
    <p>Xin chào {{ $comment->user->name ?? $comment->user->email }},</p>
    <p>Bình luận của bạn trên sản phẩm "<strong>{{ $comment->product->name }}</strong>" đã được xử lý.</p>
    @if ($reply)
        <h3>Phản hồi từ admin:</h3>
        <p>{{ $reply }}</p>
    @endif
    <h3>Thông tin bình luận:</h3>
    <p><strong>Nội dung:</strong> {{ $comment->content }}</p>
    <p><strong>Trạng thái:</strong> {{ $comment->status == 'visible' ? 'Hiển thị' : 'Ẩn' }}</p>
    <p><strong>Thời gian gửi:</strong> {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'Chưa xác định' }}</p>
    <p>Cảm ơn bạn đã đóng góp ý kiến!</p>
    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
