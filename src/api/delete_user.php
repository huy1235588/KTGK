<?php
include '../utils/db_connect.php';
include '../controller/UserController.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo UserController
$userController = new UserController($conn);

// Nếu có get param id
if (isset($_GET['id'])) {
    // Lấy id từ GET param
    $id = $_GET['id'];

    // Gọi hàm xóa user
    $result = $userController->deleteUser($id);

    // Nếu kết quả trả về là true
    if ($result === true) {
        // Chuyển hướng về trang danh sách user
        header('location: /admin/view/users');
    } else {
        // Nếu kết quả trả về là false
        // Hiển thị thông báo lỗi
        echo 'Xoá user không thành công';
    }
}