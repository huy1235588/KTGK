<?php
include '../utils/db_connect.php';
include '../controller/FriendController.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo FriendController
$userController = new FriendController($conn);

// Set Content-Type
header('Content-Type: application/json');

// Nếu có get param id
if (isset($_GET['userId']) && isset($_GET['friendId'])) {
    // Lấy id từ GET param
    $userId = $_GET['userId'];
    $friendId = $_GET['friendId'];

    // Gọi hàm sendFriendRequest
    $result = $userController->sendFriendRequest($userId, $friendId);

    // Nếu kết quả trả về là true
    if ($result === true) {
        // tra ve json
        echo json_encode([
            'success' => true,
        ]);
    } else {
        echo json_encode([
            'success' => false,
        ]);
    }
}
