<?php
include '../utils/db_connect.php';
include '../controller/UserController.php';

// Mở kết nối
$conn = MoKetNoi();

// Xử lý yêu cầu
$method = $_SERVER['REQUEST_METHOD'];

// Set Content-Type
header('Content-Type: application/json');

// Lấy dữ liệu từ body
$data = json_decode(file_get_contents('php://input'), true);

// Nếu là POST và có sort
if ($method === 'POST') {
    // Lấy offset, limit, query, sort, order
    $offset = $data['offset'];
    $limit = $data['limit'];
    $query = $data['q'];
    $sort = $data['sort'];
    $order = $data['order'];
    $columns = $data['columns'];

    // Khởi tạo UserController
    $userController = new UserController($conn);

    // Lấy 10 sản phẩm
    $users = $userController->getUserByPage($offset, $limit, $columns, $query, $sort, $order);

    // Lấy tổng số sản phẩm
    $totalUser = $userController->getTotalUsers($query);

    // Encode UTF-8
    foreach ($users as &$user) {
        foreach ($user as $key => $value) {
            if (is_string($value)) {
                $user[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }
        }
    }

    // Trả về JSON
    echo json_encode([
        'data' => $users,
        'total' => $totalUser,
    ]);
}
