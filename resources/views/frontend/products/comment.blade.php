<section class="blog-section section-b-space">
    <div class="comment-box overflow-hidden">
        <div class="leave-title">
            <h3>Bình luận</h3>
        </div>
        <div class="user-comment-box">
            <ul id="comments-list">
                @php $currentUser = auth()->user(); @endphp
                @foreach($comments as $comment)
                <li data-comment-id="{{ $comment->id }}" style="list-style: none; padding-bottom: 5px;">
                    <div class="user-box border-color">
                        <div class="user-image">
                            @php
                                $user = $comment->user;
                                $firstChar = strtoupper(substr($user->name, 0, 1));
                            @endphp
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="avatar-img" />
                            @else
                                <div class="avatar-initial">{{ $firstChar }}</div>
                            @endif
                        </div>
                        <div class="user-contain mb-3">
                            <div class="user-name">
                                <h6>{{ \Carbon\Carbon::parse($comment->created_at)->locale('vi')->isoFormat('D MMMM, YYYY') }}</h6>
                                <h5 class="text-content">{{ $user->name }}</h5>
                            </div>
                            <p style="margin-top: 5px;">{{ $comment->content }}</p>
                        </div>
                        <div class="comment-actions" style="display:flex; gap:10px; align-items:center;">
                            @if($currentUser && ($currentUser->id === $comment->user_id || $currentUser->role === 'admin'))
                                <button type="button" class="btn btn-sm btn-delete-comment" onclick="showDeleteCommentModal({{ $comment->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @endif
                            @if($currentUser)
                                <button type="button" class="btn btn-sm" onclick="toggleReplyForm('comment', {{ $comment->id }})">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- FORM trả lời cho comment --}}
                    @if($currentUser)
                    <form class="reply-form mb-3" id="reply-form-comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" style="display:none; margin-left: 40px;">
                        @csrf
                        <textarea name="reply" rows="2" placeholder="Viết trả lời..." required class="form-control mb-2"></textarea>
                        <div style="display: flex;">
                            <button type="button" class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold me-3" onclick="submitReplyComment({{ $comment->id }})">Gửi trả lời</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="toggleReplyForm('comment', {{ $comment->id }})">Hủy</button>
                        </div>
                    </form>
                    @endif

                    <ul class="replies-list" style="margin-left: 40px;">
                        @foreach($comment->replies as $reply)
                        <li data-reply-id="{{ $reply->id }}" style="list-style: none; padding-bottom: 5px;">
                            <div class="user-box">
                                <div class="user-image">
                                    @php
                                        $rUser = $reply->user;
                                        $rFirstChar = strtoupper(substr($rUser->name, 0, 1));
                                    @endphp
                                    @if($rUser->avatar)
                                        <img src="{{ asset($rUser->avatar) }}" alt="{{ $rUser->name }}" class="avatar-img" />
                                    @else
                                        <div class="avatar-initial">{{ $rFirstChar }}</div>
                                    @endif
                                </div>
                                <div class="user-contain mb-3">
                                    <div class="user-name">
                                        <h6>{{ \Carbon\Carbon::parse($reply->created_at)->locale('vi')->isoFormat('D MMMM, YYYY') }}</h6>
                                        <h5 class="text-content">{{ $rUser->name }}</h5>
                                    </div>
                                    <p style="margin-top: 5px;">{{ $reply->reply }}</p>
                                </div>
                                <div class="comment-actions" style="display:flex; gap:10px; align-items:center;">
                                    @if($currentUser && ($currentUser->id === $reply->user_id || $currentUser->role === 'admin'))
                                        <button type="button" class="btn btn-sm btn-delete-comment" onclick="showDeleteReplyModal({{ $reply->id }})">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                    @if($currentUser)
                                        <button type="button" class="btn btn-sm" onclick="toggleReplyForm('reply', {{ $reply->id }})">
                                            <i class="fa-solid fa-reply"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            {{-- FORM trả lời cho reply --}}
                            @if($currentUser)
                            <form class="reply-form mb-3" id="reply-form-reply-{{ $reply->id }}" data-reply-id="{{ $reply->id }}" style="display:none; margin-left: 40px;">
                                @csrf
                                <textarea name="reply" rows="2" placeholder="Viết trả lời..." required class="form-control mb-2"></textarea>
                                <div style="display: flex;">
                                    <button type="button" class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold me-3" onclick="submitReplyReply({{ $reply->id }}, {{ $comment->id }})">Gửi trả lời</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleReplyForm('reply', {{ $reply->id }})">Hủy</button>
                                </div>
                            </form>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="leave-box">
        <div class="leave-title mt-0">
            <h3>Để lại bình luận</h3>
        </div>
        @if($currentUser)
            <form id="comment-form">
                @csrf
                <div class="col-12 mb-3">
                    <div class="blog-input">
                        <textarea class="form-control" name="content" rows="4" placeholder="Để lại bình luận..." required></textarea>
                    </div>
                </div>
                <button class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold" type="submit">
                    Bình luận
                </button>
            </form>
        @else
            <div class="text-center mb-3">
                <span>Vui lòng </span>
                <a href="{{ route('login') }}" style="text-decoration: underline; color: #0da487; font-weight: 600;">
                    đăng nhập
                </a>
                <span> để bình luận</span>
            </div>
        @endif
    </div>
</section>


<!-- Modal sửa comment -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCommentForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCommentModalLabel">Chỉnh sửa bình luận</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <textarea name="content" rows="5" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal xóa comment -->
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteCommentForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCommentModalLabel">Xác nhận xóa bình luận</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa bình luận này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal sửa reply -->
<div class="modal fade" id="editReplyModal" tabindex="-1" aria-labelledby="editReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editReplyForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReplyModalLabel">Chỉnh sửa trả lời</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <textarea name="reply" rows="5" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal xóa reply -->
<div class="modal fade" id="deleteReplyModal" tabindex="-1" aria-labelledby="deleteReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteReplyForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteReplyModalLabel">Xác nhận xóa trả lời</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa trả lời này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .avatar-initial {
        width: 40px;
        height: 40px;
        background-color: #007bff;
        color: white;
        font-weight: bold;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        user-select: none;
        text-transform: uppercase;
    }

    .avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-contain .mb-3 p {
        padding: 5px;
    }

    .comment-actions button {
        color: #777777;
    }

    .comment-actions button:hover {
        color: #0da487;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId = {{ $product->id }};
    const csrfToken = '{{ csrf_token() }}';
    let lastOpenedReplyForm = null; // id form reply đang mở

    // Toggle form trả lời comment/reply (đảm bảo chỉ 1 form trả lời mở)
    window.toggleReplyForm = function(type, id) {
        // type: 'comment' hoặc 'reply'
        // Đóng form đang mở trước đó
        if (lastOpenedReplyForm && lastOpenedReplyForm !== `${type}-${id}`) {
            const lastForm = document.getElementById(`reply-form-${lastOpenedReplyForm}`);
            if (lastForm) lastForm.style.display = 'none';
        }
        const currentForm = document.getElementById(`reply-form-${type}-${id}`);
        if (!currentForm) return;
        if (currentForm.style.display === 'block') {
            currentForm.style.display = 'none';
            lastOpenedReplyForm = null;
        } else {
            currentForm.style.display = 'block';
            lastOpenedReplyForm = `${type}-${id}`;
        }
    };

    // Gửi bình luận mới
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const content = this.content.value.trim();
            if (!content) {
                Swal.fire({ icon: 'warning', title: 'Thông báo', text: 'Vui lòng nhập nội dung bình luận!' });
                return;
            }
            fetch(`/client/san-pham/san-pham/${productId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.reset();
                    appendComment(data.comment);
                    // Swal.fire({ icon: 'success', text: 'Bình luận thành công!' }); k can tb thi an
                } else {
                    Swal.fire({ icon: 'error', text: data.message || 'Có lỗi khi gửi bình luận' });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', text: 'Có lỗi khi gửi bình luận' }));
        });
    }

    // Gửi reply cho comment
    window.submitReplyComment = function(commentId) {
        const form = document.getElementById(`reply-form-comment-${commentId}`);
        if (!form) return Swal.fire('Lỗi', 'Form trả lời không tồn tại', 'error');
        const reply = form.querySelector('textarea[name="reply"]').value.trim();
        if (!reply) return Swal.fire('Thông báo', 'Vui lòng nhập nội dung trả lời', 'warning');
        fetch(`/client/san-pham/comment/${commentId}/reply`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reply })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                form.querySelector('textarea[name="reply"]').value = '';
                form.style.display = 'none';
                lastOpenedReplyForm = null;
                appendReply(commentId, data.reply);
                // Swal.fire({ icon: 'success', text: 'Đã trả lời!' }); k can tb thi an
            } else {
                Swal.fire({ icon: 'error', text: data.message || 'Có lỗi khi gửi trả lời' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', text: 'Có lỗi khi gửi trả lời' }));
    };

    // Gửi reply cho reply
    window.submitReplyReply = function(replyId, commentId) {
        const form = document.getElementById(`reply-form-reply-${replyId}`);
        if (!form) return Swal.fire('Lỗi', 'Form trả lời không tồn tại', 'error');
        const reply = form.querySelector('textarea[name="reply"]').value.trim();
        if (!reply) return Swal.fire('Thông báo', 'Vui lòng nhập nội dung trả lời', 'warning');
        fetch(`/client/san-pham/comment/${commentId}/reply`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reply, parent_reply_id: replyId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                form.querySelector('textarea[name="reply"]').value = '';
                form.style.display = 'none';
                lastOpenedReplyForm = null;
                appendReply(commentId, data.reply);
                Swal.fire({ icon: 'success', text: 'Đã trả lời!' });
            } else {
                Swal.fire({ icon: 'error', text: data.message || 'Có lỗi khi gửi trả lời' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', text: 'Có lỗi khi gửi trả lời' }));
    };

    // XÓA bình luận (KHÔNG RELOAD)
    window.showDeleteCommentModal = function(commentId) {
        Swal.fire({
            title: 'Bạn có chắc chắn xóa bình luận này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/client/san-pham/comment/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        removeCommentFromList(commentId);
                        Swal.fire({ icon: 'success', text: 'Đã xóa bình luận!' });
                    } else {
                        Swal.fire({ icon: 'error', text: data.message || 'Có lỗi khi xóa bình luận' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', text: 'Có lỗi khi xóa bình luận' }));
            }
        });
    };

    // XÓA trả lời (KHÔNG RELOAD)
    window.showDeleteReplyModal = function(replyId) {
        Swal.fire({
            title: 'Bạn có chắc chắn xóa trả lời này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/client/san-pham/reply/${replyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        removeReplyFromList(replyId);
                        Swal.fire({ icon: 'success', text: 'Đã xóa trả lời!' });
                    } else {
                        Swal.fire({ icon: 'error', text: data.message || 'Có lỗi khi xóa trả lời' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', text: 'Có lỗi khi xóa trả lời' }));
            }
        });
    };

    // --- APPEND (realtime) ---
    function appendComment(comment) {
        const user = comment.user;
        const firstChar = user.name.charAt(0).toUpperCase();
        let avatar = user.avatar
            ? `<img src="${user.avatar}" alt="${user.name}" class="avatar-img" />`
            : `<div class="avatar-initial">${firstChar}</div>`;
        const created = new Date(comment.created_at);
        const dateStr = created.toLocaleDateString('vi-VN', {day: '2-digit', month: 'long', year: 'numeric'});
        const html = `
        <li data-comment-id="${comment.id}" style="list-style: none; padding-bottom: 5px;">
            <div class="user-box border-color">
                <div class="user-image">${avatar}</div>
                <div class="user-contain mb-3">
                    <div class="user-name">
                        <h6>${dateStr}</h6>
                        <h5 class="text-content">${user.name}</h5>
                    </div>
                    <p style="margin-top: 5px;">${comment.content}</p>
                </div>
                <div class="comment-actions" style="display:flex; gap:10px; align-items:center;">
                    <button type="button" class="btn btn-sm btn-delete-comment" onclick="showDeleteCommentModal(${comment.id})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button type="button" class="btn btn-sm" onclick="toggleReplyForm('comment', ${comment.id})">
                        <i class="fa-solid fa-reply"></i>
                    </button>
                </div>
            </div>
            <form class="reply-form mb-3" id="reply-form-comment-${comment.id}" data-comment-id="${comment.id}" style="display:none; margin-left: 40px;">
                <textarea name="reply" rows="2" placeholder="Viết trả lời..." required class="form-control mb-2"></textarea>
                <div style="display: flex;">
                    <button type="button" class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold me-3" onclick="submitReplyComment(${comment.id})">Gửi trả lời</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleReplyForm('comment', ${comment.id})">Hủy</button>
                </div>
            </form>
            <ul class="replies-list" style="margin-left: 40px;"></ul>
        </li>`;
        document.getElementById('comments-list').insertAdjacentHTML('afterbegin', html);
    }

    function appendReply(commentId, reply) {
        const user = reply.user;
        const firstChar = user.name.charAt(0).toUpperCase();
        let avatar = user.avatar
            ? `<img src="${user.avatar}" alt="${user.name}" class="avatar-img" />`
            : `<div class="avatar-initial">${firstChar}</div>`;
        const created = new Date(reply.created_at);
        const dateStr = created.toLocaleDateString('vi-VN', {day: '2-digit', month: 'long', year: 'numeric'});
        const html = `
        <li data-reply-id="${reply.id}" style="list-style: none; padding-bottom: 5px;">
            <div class="user-box">
                <div class="user-image">${avatar}</div>
                <div class="user-contain mb-3">
                    <div class="user-name">
                        <h6>${dateStr}</h6>
                        <h5 class="text-content">${user.name}</h5>
                    </div>
                    <p style="margin-top: 5px;">${reply.reply}</p>
                </div>
                <div class="comment-actions" style="display:flex; gap:10px; align-items:center;">
                    <button type="button" class="btn btn-sm btn-delete-comment" onclick="showDeleteReplyModal(${reply.id})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button type="button" class="btn btn-sm" onclick="toggleReplyForm('reply', ${reply.id})">
                        <i class="fa-solid fa-reply"></i>
                    </button>
                </div>
            </div>
            <form class="reply-form mb-3" id="reply-form-reply-${reply.id}" data-reply-id="${reply.id}" style="display:none; margin-left: 40px;">
                <textarea name="reply" rows="2" placeholder="Viết trả lời..." required class="form-control mb-2"></textarea>
                <div style="display: flex;">
                    <button type="button" class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold me-3" onclick="submitReplyReply(${reply.id}, ${reply.comment_id})">Gửi trả lời</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleReplyForm('reply', ${reply.id})">Hủy</button>
                </div>
            </form>
        </li>`;
        const ul = document.querySelector(`[data-comment-id="${commentId}"] .replies-list`);
        if(ul) ul.insertAdjacentHTML('beforeend', html);
    }

    // Xóa khỏi DOM
    function removeCommentFromList(commentId) {
        const el = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (el) el.remove();
    }
    function removeReplyFromList(replyId) {
        const el = document.querySelector(`[data-reply-id="${replyId}"]`);
        if (el) el.remove();
    }
});
</script>