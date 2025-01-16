<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        padding: 20px 12px;
        margin: 10px 0;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        z-index: 10;
        transition: all 0.2s ease-in-out;
    }

    .notification:active {
        transform: scale(0.9);
    }

    .notification-close-btn {
        width: auto;
        margin: auto 0;
        margin-left: 10px;
        padding: 5px 7px;
        margin-left: 5px;
        background: transparent;
        color: #1a6f7a;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }

    .notification-close-btn:hover {
        background-color:rgba(26, 111, 122, 0.46);
        color: #1a6f7a;
    }
</style>

<?php
class Notification
{
    private $styles = [
        'info' => 'background-color: #e0f7fa; color: #00796b; border: 1px solid #4db6ac;',
        'success' => 'background-color: #e8f5e9; color: #2e7d32; border: 1px solid #81c784;',
        'warning' => 'background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffb74d;',
        'error' => 'background-color: #ffebee; color: #c62828; border: 1px solid #e57373;'
    ];

    public function render($message, $type = 'info')
    {
        // Lấy style tương ứng
        $style = $this->styles[$type] ?? $this->styles['info'];

        // Hiển thị thông báo
        echo <<<HTML
            <div class="notification" style="$style" role="alert">
                <span>$message</span>
                <button type="button" class="notification-close-btn" onclick="this.parentElement.style.display='none';">
                    &times;
                </button>
            </div>
            <script src="components/notification.js"></script>
        HTML;
    }
}
