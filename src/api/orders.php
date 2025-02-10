<?php
include '../utils/db_connect.php';
include '../controller/OrderController.php';

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

    // Khởi tạo OrderController
    $orderController = new OrderController($conn);

    // Lấy 10 sản phẩm
    $orders = $orderController->getOrdersByPage($offset, $limit, $columns, $query, $sort, $order);

    // Lấy tổng số sản phẩm
    $totalOrder = $orderController->getTotalOrders($query);

    // Encode UTF-8
    foreach ($orders as &$order) {
        foreach ($order as $key => $value) {
            if (is_string($value)) {
                $order[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }
        }
    }

    // Trả về JSON
    echo json_encode([
        'data' => $orders,
        'total' => $totalOrder,
    ]);
}
