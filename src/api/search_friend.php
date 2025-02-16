<?php
include '../utils/db_connect.php';
include '../controller/UserController.php';
include '../controller/FriendController.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo UserController
$userController = new UserController($conn);
$friendController = new FriendController($conn);

// Set Content-Type
header('Content-Type: application/json');

// Nếu có get param id
if (isset($_GET['userName']) && isset($_GET['userId'])) {
    // Lấy id từ GET param
    $userName = $_GET['userName'];
    $userId = $_GET['userId'];

    // Gọi hàm tìm user theo username
    $result = $userController->findUserByUsername($userName);

    // Kiểm tra xem user có phải là bạn bè không
    foreach ($result as $key => $value) {
        $result[$key]['isFriend'] = $friendController->isFriend($userId, $value['id']);
    }

    // Kiểm tra xem user có phải là người gửi yêu cầu kết bạn không
    foreach ($result as $key => $value) {
        $result[$key]['isSentRequest'] = $friendController->isFriendRequestSent($userId, $value['id']);
    }

    // Trả về JSON
    echo json_encode([
        'success' => true,
        'friends' => $result
    ]);
}