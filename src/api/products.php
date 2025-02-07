<?php
include '../utils/db_connect.php';
include '../controller/ProductController.php';

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

    // Khởi tạo ProductController
    $productController = new ProductController($conn);

    // Lấy 10 sản phẩm
    $products = $productController->getProductsByPage($offset, $limit, $columns, $query, $sort, $order);

    // Lấy tổng số sản phẩm
    $totalProducts = $productController->getTotalProducts($query);

    // Encode UTF-8
    foreach ($products as &$product) {
        foreach ($product as $key => $value) {
            if (is_string($value)) {
                $product[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }
        }
    }

    // Trả về JSON
    echo json_encode([
        'data' => $products,
        'total' => $totalProducts,
    ]);
}
