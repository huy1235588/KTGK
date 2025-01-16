<?php
// Kiểm tra session đã được khởi tạo chưa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function setNotification($message, $type = 'success')
{
    $_SESSION['notification'] = [
        'message' => $message,
        'type' => $type, // success, error, warning, info
    ];
}

function getNotification()
{
    if (isset($_SESSION['notification'])) {
        $notification = $_SESSION['notification'];
        unset($_SESSION['notification']); // Xóa sau khi hiển thị
        return $notification;
    }
    return null;
}
