<?php
include '../utils/db_connect.php';
include '../components/notification.php';

// Mở kết nối
$conn = MoKetNoi();

// Xử lý yêu cầu
$method = $_SERVER['REQUEST_METHOD'];

// Set Content-Type
header('Content-Type: application/json');

// Nếu là POST
if ($method === 'POST') {
    // Lấy thông tin sản phẩm
    $productId = $_POST['productId'] ?? null;

    if (isset($productId)) {
        // Thêm vào productId vào localStorage
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $isExist = false;
        foreach ($_SESSION['cart'] as $item) {
            if ($item['productId'] === $productId) {
                $isExist = true;
                break;
            }
        }

        // Nếu sản phẩm chưa tồn tại trong giỏ hàng
        if (!$isExist) {
            array_push($_SESSION['cart'], [
                'productId' => $productId,
            ]);
        }

        // Trả về json
        echo json_encode(array(
            'message' => 'Product added to cart successfully.',
            'cart' => $_SESSION['cart']
        ));
    } else {
        echo 'Product ID not provided.';
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

// Xử lý xoá sản phẩm khỏi giỏ hàng
else if ($method === 'DELETE') {
    // Lấy thông tin sản phẩm
    $productId = $_GET['productId'] ?? null;

    if (isset($productId)) {
        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $isExist = false;
        $index = -1;
        foreach ($_SESSION['cart'] as $i => $item) {
            if ($item['productId'] === $productId) {
                $isExist = true;
                $index = $i;
                break;
            }
        }

        // Nếu sản phẩm đã tồn tại trong giỏ hàng
        if ($isExist) {
            array_splice($_SESSION['cart'], $index, 1);
        }

        // Trả về json
        echo json_encode(array(
            'message' => 'Product removed from cart successfully.',
            'cart' => $_SESSION['cart']
        ));
    } else {
        echo 'Product ID not provided.';
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

// Nếu không phải POST hoặc DELETE
else {
    // Trả về lỗi
    http_response_code(405);
    echo 'Method not allowed';
}
