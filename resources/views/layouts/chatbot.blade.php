<!-- Chatbot Widget -->
<div id="chatbot" class="chatbot-container">
  <header class="chatbot-header">
    <span>Trợ lý ảo Quà Quê</span>
    <button id="clear-chat-btn" title="Xóa lịch sử chat">Xóa chat</button>
  </header>
  <div id="chatbot-messages" class="chatbot-messages"></div>
  <form id="chatbot-form" class="chatbot-form">
    <input type="text" id="chatbot-input" placeholder="Nhập tin nhắn..." autocomplete="off" required />
    <button type="submit">Gửi</button>
  </form>
</div>

<style>
  .chatbot-container {
    width: 320px;
    max-height: 500px;
    display: flex;
    flex-direction: column;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    overflow: hidden;
  }
  .chatbot-header {
    background: #0da487;
    color: white;
    padding: 10px 15px;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
  }
  #clear-chat-btn {
    background: #e9e9eb;
    border: none;
    border-radius: 15px;
    padding: 6px 12px;
    cursor: pointer;
    color: #0da487;
    font-weight: 600;
    font-size: 13px;
  }
  .chatbot-messages {
    flex-grow: 1;
    padding: 15px;
    background: #f9f9f9;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-height: 350px;
  }
  .message {
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 20px;
    font-size: 14px;
    line-height: 1.4;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    word-wrap: break-word;
  }
  .user-message {
    background: #e9e9eb;
    color: #000;
    align-self: flex-end;
    border-bottom-right-radius: 5px;
  }
  .bot-message {
    background: #0da487;
    color: white;
    align-self: flex-start;
    border-bottom-left-radius: 5px;
  }
  .bot-message a {
    color: #fff !important;
    text-decoration: underline;
    font-weight: 600;
  }
  .chatbot-form {
    display: flex;
    padding: 10px 15px;
    border-top: 1px solid #ddd;
  }
  #chatbot-input {
    flex-grow: 1;
    border-radius: 20px;
    border: 1px solid #ccc;
    padding: 10px 15px;
    font-size: 14px;
    outline-offset: 2px;
  }
  #chatbot-input:focus {
    border-color: #0da487;
  }
  .chatbot-form button {
    background: #0da487;
    color: white;
    border: none;
    border-radius: 20px;
    padding: 0 20px;
    margin-left: 10px;
    cursor: pointer;
    font-weight: 600;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    const clearBtn = document.getElementById('clear-chat-btn');
    const storageKey = 'quaque-chat-history';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const chatUrl = "{{ route('client.chatbot.send') }}" || "http://localhost:81/client/chatbot";

    // Hàm chuyển markdown đơn giản thành HTML
    function markdownToHtml(text) {
      return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/\[(.*?)\]\((\/client\/danh-muc\/[^\)]+)\)/g, '<a href="$2" class="internal-link">$1</a>')
        .replace(/\[(.*?)\]\((\/client\/san-pham\/[^\)]+)\)/g, '<a href="$2" class="internal-link">$1</a>')
        .replace(/\[(.*?)\]\((https?:\/\/.*?)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>')
        .replace(/\n/g, '<br>');
    }

    // Thêm tin nhắn vào chatbox và lưu lịch sử
    function addMessage(text, className) {
      const div = document.createElement('div');
      div.classList.add('message', className);
      if (className === 'bot-message') {
        div.innerHTML = markdownToHtml(text);
      } else {
        div.textContent = text;
      }
      messages.appendChild(div);
      messages.scrollTop = messages.scrollHeight;
      saveHistory();
      return div;
    }

    // Lưu lịch sử chat vào localStorage
    function saveHistory() {
      const history = [];
      messages.querySelectorAll('.message').forEach(msg => {
        history.push({
          className: msg.classList.contains('bot-message') ? 'bot-message' : 'user-message',
          html: msg.innerHTML
        });
      });
      localStorage.setItem(storageKey, JSON.stringify(history));
    }

    // Load lịch sử chat
    function loadHistory() {
      messages.innerHTML = '';
      const history = JSON.parse(localStorage.getItem(storageKey) || '[]');
      if (history.length) {
        history.forEach(item => {
          const div = document.createElement('div');
          div.classList.add('message', item.className);
          div.innerHTML = item.html;
          messages.appendChild(div);
        });
      } else {
        addMessage('Chào bạn! Tôi là trợ lý ảo của Quà Quê. Tôi có thể giúp gì cho bạn?', 'bot-message');
      }
      messages.scrollTop = messages.scrollHeight;
    }

    // Gửi tin nhắn
    form.addEventListener('submit', e => {
      e.preventDefault();
      const msg = input.value.trim();
      if (!msg) return;
      addMessage(msg, 'user-message');
      input.value = '';

      // Loading
      const loadingMsg = addMessage('...', 'bot-message');
      loadingMsg.id = 'loading-indicator';

      fetch(chatUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ message: msg })
      })
      .then(res => {
        if (!res.ok) throw new Error('Network error');
        return res.json();
      })
      .then(data => {
        const loading = document.getElementById('loading-indicator');
        if (loading) loading.remove();
        if (data.reply) {
          addMessage(data.reply, 'bot-message');
        } else {
          addMessage('Lỗi: ' + (data.error || 'Không thể nhận phản hồi.'), 'bot-message');
        }
      })
      .catch(() => {
        const loading = document.getElementById('loading-indicator');
        if (loading) loading.remove();
        addMessage('Đã xảy ra lỗi kết nối. Vui lòng thử lại.', 'bot-message');
      });
    });

    // Xóa lịch sử chat
    clearBtn.addEventListener('click', () => {
      localStorage.removeItem(storageKey);
      messages.innerHTML = '';
      addMessage('Chào bạn! Tôi là trợ lý ảo của Quà Quê. Tôi có thể giúp gì cho bạn?', 'bot-message');
    });

    // Link nội bộ click
    messages.addEventListener('click', e => {
      if (e.target.tagName === 'A' && e.target.classList.contains('internal-link')) {
        e.preventDefault();
        window.location.href = e.target.href;
      }
    });

    loadHistory();
  });
</script>
