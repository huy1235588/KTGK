<?php
include '../utils/db_connect.php';
include '../controller/UserController.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo UserController
$userController = new UserController($conn);

// Set Content-Type
header('Content-Type: application/json');

// Nếu có get param id
if (isset($_GET['userName'])) {
    // Lấy id từ GET param
    $userName = $_GET['userName'];

    // Gọi hàm xóa user
    $result = $userController->findUserByUsername($userName);

    // Trả về JSON
    echo json_encode([
        'success' => true,
        'friends' => $result
    ]);
}