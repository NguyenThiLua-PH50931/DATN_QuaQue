{{-- Chatbot Floating Widget --}}
<div id="chatbot-header" style="display: flex; justify-content: space-between;">
    <span>Trợ lý ảo Quà Quê</span>
    <button id="clear-chat-history-btn" type="button">
        Xóa chat
    </button>
    <!-- Không cần nút đóng ở đây -->
</div>
<div id="chatbot-box">
    <div class="message bot-message">Chào bạn! Tôi là trợ lý ảo của Quà Quê. Tôi có thể giúp gì cho bạn?</div>
</div>
<div id="chatbot-input-container">
    <form id="chatbot-form">
        <input type="text" id="chatbot-message-input" placeholder="Nhập tin nhắn..." autocomplete="off" required>
        <button type="submit">Gửi</button>
    </form>

</div>


<style>
    .theme-setting-2.active {
        padding: 0px 0px 0px 0px;
        height: 500px;
        display: flex;
        flex-direction: column;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    #chatbot-header {
        background-color: #0da487;
        color: white;
        padding: 5px 15px;
        font-weight: bold;
        display: flex;
        align-items: center;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        border-bottom: #f0f0f0 1px solid;
    }

    #chatbot-box {
        flex-grow: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 370px;
        overflow-y: auto;
    }

    .message {
        padding: 10px 15px;
        border-radius: 20px;
        max-width: 80%;
        line-height: 1.4;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        font-size: 15px;
    }

    .user-message {
        background-color: #e9e9eb;
        color: #000;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }

    .bot-message {
        background-color: #0da487;
        color: #fff;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }

    #chatbot-input-container {
        padding: 10px;
        border-top: 1px solid #f0f0f0;
    }

    #chatbot-form {
        display: flex;
    }

    #chatbot-message-input {
        flex-grow: 1;
        border: 1px solid #ccc;
        border-radius: 20px;
        padding: 10px 15px;
        margin-right: 10px;
    }

    #chatbot-message-input:focus {
        outline: none;
        border-color: #0da487;
    }

    #chatbot-form button {
        background-color: #0da487;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 10px 20px;
        cursor: pointer;
    }

    .bot-message a {
        color: #fff !important;
        text-decoration: underline;
        font-weight: bold;
    }

    #clear-chat-history-btn {
        background: #e9e9eb;
        color: #0da487;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        padding: 10px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chatbot-form');
        const messageInput = document.getElementById('chatbot-message-input');
        const chatBox = document.getElementById('chatbot-box');
        const clearBtn = document.getElementById('clear-chat-history-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ?
            document.querySelector('meta[name="csrf-token"]').getAttribute('content') :
            '';
        // Sửa lại cho phù hợp route của vợ
        const chatUrl = "{{ route('client.chatbot.send') }}" || "http://localhost:81/client/chatbot";
        const chatHistoryKey = 'quaque-chat-history';

        // === Hiển thị lại lịch sử khi load trang ===
        renderHistoryFromStorage();

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message === '') return;

            appendMessage(message, 'user-message');
            messageInput.value = '';

            // Hiển thị loading indicator
            const loadingIndicator = appendMessage('...', 'bot-message');
            loadingIndicator.id = 'loading-indicator';

            fetch(chatUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const loading = document.getElementById('loading-indicator');
                    if (loading) loading.remove();

                    if (data.reply) {
                        appendMessage(data.reply, 'bot-message');
                    } else {
                        appendMessage('Lỗi: ' + (data.error || 'Không thể nhận phản hồi.'), 'bot-message');
                    }
                })
                .catch(error => {
                    const loading = document.getElementById('loading-indicator');
                    if (loading) loading.remove();
                    appendMessage('Đã xảy ra lỗi kết nối. Vui lòng thử lại.', 'bot-message');
                });
        });

        // Nút xóa lịch sử chat
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                localStorage.removeItem(chatHistoryKey);
                chatBox.innerHTML = '';
                appendMessage('Chào bạn! Tôi là trợ lý ảo của Quà Quê. Tôi có thể giúp gì cho bạn?', 'bot-message');
            });
        }

        // ==== Hàm markdownToHtml hỗ trợ link sản phẩm, danh mục, link ngoài ====
        function markdownToHtml(str) {
            // Đậm
            str = str.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Nghiêng
            str = str.replace(/\*(.*?)\*/g, '<em>$1</em>');
            // Link danh mục nội bộ
            str = str.replace(/\[(.*?)\]\((\/client\/danh-muc\/[^\)]+)\)/g, '<a href="$2" class="internal-link">$1</a>');
            // Link sản phẩm nội bộ
            str = str.replace(/\[(.*?)\]\((\/client\/san-pham\/[^\)]+)\)/g, '<a href="$2" class="internal-link">$1</a>');
            // Link ngoài (https)
            str = str.replace(/\[(.*?)\]\((https?:\/\/.*?)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
            // Xuống dòng
            str = str.replace(/\n/g, '<br>');
            return str;
        }

        // ==== Tạo message và lưu lịch sử ====
        function appendMessage(text, className) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', className);
            if (className === 'bot-message') {
                messageElement.innerHTML = markdownToHtml(text);
            } else {
                messageElement.textContent = text;
            }
            chatBox.appendChild(messageElement);
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
            saveHistoryToStorage();
            return messageElement;
        }

        // ==== Lưu lịch sử vào localStorage ====
        function saveHistoryToStorage() {
            const items = [];
            chatBox.querySelectorAll('.message').forEach(msg => {
                items.push({
                    className: msg.classList.contains('bot-message') ? 'bot-message' : 'user-message',
                    html: msg.innerHTML
                });
            });
            localStorage.setItem(chatHistoryKey, JSON.stringify(items));
        }

        // ==== Hiển thị lịch sử từ localStorage ====
        function renderHistoryFromStorage() {
            chatBox.innerHTML = '';
            const history = JSON.parse(localStorage.getItem(chatHistoryKey) || '[]');
            if (history.length) {
                history.forEach(item => {
                    const div = document.createElement('div');
                    div.classList.add('message', item.className);
                    div.innerHTML = item.html;
                    chatBox.appendChild(div);
                });
            } else {
                // Luôn có câu chào khi chưa có chat
                appendMessage('Chào bạn! Tôi là trợ lý ảo của Quà Quê. Tôi có thể giúp gì cho bạn?', 'bot-message');
            }
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }

        // ==== Xử lý click link nội bộ, giữ lại history ====
        chatBox.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && e.target.classList.contains('internal-link')) {
                e.preventDefault();
                const href = e.target.getAttribute('href');
                // Lưu history đã lưu rồi, nên chỉ chuyển trang luôn
                window.location.href = href;
            }
        });
    });
</script>