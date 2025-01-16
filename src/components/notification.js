document.addEventListener('DOMContentLoaded', () => {
    const notification = document.querySelector('.notification');
    if (notification) {
        // Đóng thông báo khi nhấn
        notification.addEventListener('click', () => {
            notification.style.animation = 'fade-out 0.5s ease forwards';
        });
    }
    
    // Ẩn thông báo sau 5 giây
    setTimeout(function () {
        var alertBox = document.querySelector('.notification');
        if (alertBox) {
            alertBox.style.display = 'none';
        }
    }, 500000);
});
