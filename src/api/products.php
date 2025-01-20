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

// Nếu là GET và có sort
if ($method === 'GET' && isset($_GET['sort'])) {
    // Lấy offset và limit
    $offset = $_GET['offset'];
    $limit = $_GET['limit'];
    $sort = $_GET['sort'];
    $order = $_GET['order'];
    $query = $_GET['q'];

    // Khởi tạo ProductController
    $productController = new ProductController($conn);

    // Lấy 10 sản phẩm
    $products = $productController->getProductsByPage($offset, $limit, $query, $sort, $order);

    // Lấy tổng số sản phẩm
    $totalProducts = $productController->getTotalProducts($query);
    
    // Trả về JSON
    echo json_encode([
        'data' => $products,
        'total' => $totalProducts,
    ]);
}

// Nếu là GET
else if ($method === 'GET') {
    // Lấy offset và limit
    $offset = $_GET['offset'];
    $limit = $_GET['limit'];
    $query = $_GET['q'];

    // Khởi tạo ProductController
    $productController = new ProductController($conn);

    // Lấy 10 sản phẩm
    $products = $productController->getProductsByPage($offset, $limit, $query);

    // Lấy tổng số sản phẩm
    $totalProducts = $productController->getTotalProducts($query);

    // Trả về JSON
    echo json_encode([
        'data' => $products,
        'total' => $totalProducts,
    ]);
}
