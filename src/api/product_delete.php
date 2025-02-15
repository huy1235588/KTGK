<?php
include '../utils/db_connect.php';
include '../controller/ProductController.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo ProductController
$productController = new ProductController($conn);

// Set Content-Type
header('Content-Type: application/json');

// Nếu có get param id
if (isset($_GET['id'])) {
    // Lấy id từ GET param
    $id = $_GET['id'];

    // Gọi hàm xóa user
    $result = $productController->deleteProduct($id);

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