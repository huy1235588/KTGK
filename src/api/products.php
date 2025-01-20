<?php
include '../utils/db_connect.php';
include '../components/notification.php';
include '../controller/ProductController.php';

// Mở kết nối
$conn = MoKetNoi();

// Xử lý yêu cầu
$method = $_SERVER['REQUEST_METHOD'];

// Set Content-Type
header('Content-Type: application/json');

// Nếu là GET
if ($method === 'GET') {
    // Lấy offset và limit
    $offset = $_GET['offset'];
    $limit = $_GET['limit'];

    // Khởi tạo ProductController
    $productController = new ProductController($conn);

    // Lấy 10 sản phẩm
    $products = $productController->getProductsByPage($offset, $limit);

    // Lấy tổng số sản phẩm
    $totalProducts = $productController->getTotalProducts();

    // Trả về JSON
    echo json_encode([
        'data' => $products,
        'total' => $totalProducts,
    ]);
}
