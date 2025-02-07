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

    // Trả về JSON
    echo json_encode([
        'data' => $products,
        'total' => $totalProducts,
    ]);
}

// Nếu là GET
// else if ($method === 'GET') {
//     // Lấy offset và limit
//     $offset = $_GET['offset'];
//     $limit = $_GET['limit'];
//     $query = $_GET['q'];

//     // Khởi tạo ProductController
//     $productController = new ProductController($conn);

//     // Lấy 10 sản phẩm
//     $products = $productController->getProductsByPage($offset, $limit, $query);

//     // Lấy tổng số sản phẩm
//     $totalProducts = $productController->getTotalProducts($query);

//     // Trả về JSON
//     echo json_encode([
//         'data' => $products,
//         'total' => $totalProducts,
//     ]);
// }
