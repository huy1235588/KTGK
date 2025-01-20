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

// Nếu là GET và có id
if ($method === 'GET' && isset($_GET['id'])) {
    // Lấy id
    $id = $_GET['id'];

    // Khởi tạo ProductController
    $productController = new ProductController($conn);

    // Lấy sản phẩm theo id
    $product = $productController->getProductById($id);

    // Trả về JSON
    echo json_encode($product);
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
