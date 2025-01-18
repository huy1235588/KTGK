<?php

// Hàm kiểm tra đăng nhập
function authMiddleware()
{
    session_start();

    // Kiểm  tra người dùng có đăng nhập hay chưa
    if (!isset($_SESSION['username'])) {
        // Nếu chưa thì chuyển hướng đến trang đăng nhập
        header('Location: /dangnhap.php');
        exit();
    }
}
