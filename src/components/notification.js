function setNotification(message, type = 'info', duration = 3000) {
    // Tạo container nếu chưa có
    let notificationContainer = document.getElementById('notification-container');
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        document.body.appendChild(notificationContainer);
    }

    // Tạo thông báo
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    // Kiểu loại thông báo
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#4CAF50'; // Màu xanh lá
            break;
        case 'error':
            notification.style.backgroundColor = '#F44336'; // Màu đỏ
            break;
        case 'warning':
            notification.style.backgroundColor = '#FFC107'; // Màu vàng
            break;
        default:
            notification.style.backgroundColor = '#2196F3'; // Màu xanh dương
            break;
    }

    // Nếu số lượng thông báo quá nhiều thì xóa bớt
    const notifications = notificationContainer.getElementsByClassName('notification');
    if (notifications.length >= 5) {
        notifications[0].remove();
    }

    // Thêm thông báo vào container
    notificationContainer.appendChild(notification);

    // Hiện thông báo
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 10);

    // Tự động ẩn và xóa thông báo
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.addEventListener('transitionend', () => {
            notification.remove();
        });
    }, duration);
}