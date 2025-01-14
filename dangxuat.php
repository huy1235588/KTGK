<?php
session_start();

// Xóa tất cả các session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: trangchu.php");
exit();
// <a href="dangxuat.php" class="logout-button">Đăng Xuất</a>
