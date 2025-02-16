<?php
include '../utils/db_connect.php';
include '../controller/FriendController.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo FriendController
$userController = new FriendController($conn);

// Set Content-Type
header('Content-Type: application/json');

// Nếu ;à POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy id từ POST param
    $action = $_POST['action'];
    $userId = $_POST['userId'];
    $friendId = $_POST['friendId'];

    // Nếu action là accept
    if ($action === 'accept') {
        // Gọi hàm acceptFriendRequest
        $result = $userController->acceptFriendRequest($userId, $friendId);

        // Nếu kết quả trả về là true
        if ($result === true) {
            // tra ve json
            echo json_encode([
                'success' => true,
                'friendId' => $friendId,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'friendId' => $friendId,
            ]);
        }
    } else if ($action === 'decline') {
        // Gọi hàm declineFriendRequest
        $result = $userController->rejectFriendRequest($userId, $friendId);

        // Nếu kết quả trả về là true
        if ($result === true) {
            // tra ve json
            echo json_encode([
                'success' => true,
                'friendId' => $friendId,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'friendId' => $friendId,
            ]);
        }
    }
}
