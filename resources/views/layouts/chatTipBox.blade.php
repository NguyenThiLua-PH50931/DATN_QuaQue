<div id="chat-tip-box" class="chat-tip-box" style="display:none;">
    <button id="chat-tip-close" class="chat-tip-close" aria-label="Đóng tip">×</button>
    <div class="chat-tip-content">
        Bạn khó khăn trong việc tìm sản phẩm?<br>
        Hãy nhờ trợ lý ảo Quà Quê hỗ trợ nhé!
    </div>
    <div class="chat-tip-arrow"></div>
</div>

<style>
    .chat-tip-box {
        position: absolute;
        background: #0da487;
        color: white;
        padding: 10px 12px;
        border-radius: 8px;
        width: 150px;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        line-height: 1.3;
        display: flex;
        flex-direction: column;
        user-select: none;
        right: auto;
        white-space: normal;
    }

    .chat-tip-content {
        padding: 0 5px;
    }

    .chat-tip-close {
        align-self: flex-end;
        background: transparent;
        border: none;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-bottom: 4px;
        padding: 0;
        line-height: 1;
    }

    /* Mũi tên tam giác ở dưới tip */
    .chat-tip-arrow {
        position: absolute;
        width: 0;
        height: 0;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        border-top: 10px solid #0da487;
        bottom: -10px;
        /* 10px dưới đáy */
        /* Canh giữa mũi tên dựa trên tip box */
        left: auto;
    }

    .back-to-top {
        display: none;
        /* phải có dòng này để ẩn lúc đầu */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipBox = document.getElementById('chat-tip-box');
    const targetBtn = document.querySelector('.btn.setting-button');
    const closeBtn = document.getElementById('chat-tip-close');

    const DISPLAY_INTERVAL = 30 * 60 * 1000; // 30 phút
    const DISPLAY_DURATION = 30 * 1000; // 30 giây
    const DELAY_SHOW = 15 * 1000; // 15 giây delay trước khi hiện
    const SCROLL_TRIGGER = 500; // cuộn 500px
    const IDLE_TIME = 2 * 60 * 1000; // 2 phút idle

    const STORAGE_KEY = 'chatTipBoxHiddenAt';

    let tipVisible = false;
    let idleTimer = null;
    let scrollTriggered = false;
    let delayTimer = null;

    function setTipPosition() {
        if (!tipBox || !targetBtn) return;

        // Hiện tạm tip box để lấy kích thước chính xác
        tipBox.style.visibility = 'hidden';
        tipBox.style.display = 'flex';

        const btnOffsetTop = targetBtn.offsetTop;
        const btnOffsetLeft = targetBtn.offsetLeft;

        const tipHeight = tipBox.offsetHeight;
        const tipWidth = tipBox.offsetWidth;

        const top = btnOffsetTop - tipHeight - 15;
        const left = btnOffsetLeft - tipWidth - 10 + 47;

        tipBox.style.top = top + 'px';
        tipBox.style.left = left + 'px';

        const arrow = tipBox.querySelector('.chat-tip-arrow');
        if (arrow) {
            const btnCenterX = btnOffsetLeft + targetBtn.offsetWidth / 2;
            let arrowLeft = btnCenterX - left - 8;
            arrow.style.left = arrowLeft + 'px';
        }

        // Hiện lại tip box
        tipBox.style.visibility = 'visible';
    }

    // Hiệu ứng fadeIn
    function fadeIn(element, duration = 300) {
        element.style.opacity = 0;
        element.style.display = 'flex';
        let last = +new Date();
        const tick = function() {
            element.style.opacity = +element.style.opacity + (new Date() - last) / duration;
            last = +new Date();
            if (+element.style.opacity < 1) {
                requestAnimationFrame(tick);
            } else {
                element.style.opacity = 1;
            }
        };
        tick();
    }

    // Hiệu ứng fadeOut
    function fadeOut(element, duration = 300) {
        element.style.opacity = 1;
        let last = +new Date();
        const tick = function() {
            element.style.opacity = +element.style.opacity - (new Date() - last) / duration;
            last = +new Date();
            if (+element.style.opacity > 0) {
                requestAnimationFrame(tick);
            } else {
                element.style.opacity = 0;
                element.style.display = 'none';
            }
        };
        tick();
    }

    function showTipBox() {
        if (!tipBox || !targetBtn) return;
        if (tipVisible) return;

        const lastHiddenAt = localStorage.getItem(STORAGE_KEY);
        const now = Date.now();
        if (lastHiddenAt && now - parseInt(lastHiddenAt) < DISPLAY_INTERVAL) {
            return;
        }

        setTipPosition();

        fadeIn(tipBox);
        tipVisible = true;

        // Ẩn sau DISPLAY_DURATION
        setTimeout(() => {
            hideTipBox();
        }, DISPLAY_DURATION);

        localStorage.setItem(STORAGE_KEY, Date.now().toString());
    }

    function hideTipBox(saveTime = false) {
        if (!tipBox || !tipVisible) return;

        fadeOut(tipBox);
        tipVisible = false;

        if (saveTime) {
            localStorage.setItem(STORAGE_KEY, Date.now().toString());
        }
    }

    // Reset bộ đếm idle
    function resetIdleTimer() {
        if (idleTimer) clearTimeout(idleTimer);
        idleTimer = setTimeout(() => {
            showTipBox();
        }, IDLE_TIME);
    }

    // Lắng nghe các sự kiện người dùng để reset bộ đếm idle
    ['mousemove', 'keydown', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, () => {
            resetIdleTimer();
        });
    });

    // Trigger khi cuộn đủ SCROLL_TRIGGER px
    window.addEventListener('scroll', () => {
        if (!scrollTriggered && window.scrollY > SCROLL_TRIGGER) {
            showTipBox();
            scrollTriggered = true;
        }
    });

    // Delay 15s sau khi load trang mới show tip
    delayTimer = setTimeout(() => {
        showTipBox();
    }, DELAY_SHOW);

    // Ẩn khi bấm nút đóng hoặc nút trợ lý ảo
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            hideTipBox(true);
        });
    }

    if (targetBtn) {
        targetBtn.addEventListener('click', () => {
            hideTipBox(true);
        });
    }

    // Khởi động bộ đếm idle lần đầu
    resetIdleTimer();

});
</script>
