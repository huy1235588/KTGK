<?php
include '../utils/db_connect.php';
include '../components/notification.php';

// Mở kết nối
$conn = MoKetNoi();

// Xử lý yêu cầu
$method = $_SERVER['REQUEST_METHOD'];

// Nếu là POST
if ($method === 'POST') {
    // Lấy thông tin sản phẩm
    $productId = $_POST['productId'] ?? null;
    print_r($productId);

    if (isset($productId)) {
        // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
        $sqlCheck = "SELECT *
        FROM cart
        WHERE product_id = $productId";
        $result = $conn->query($sqlCheck);

        if ($result->num_rows > 0) {
            // Cập nhật số lượng
            $sqlUpdate = "UPDATE cart
            SET quantity = quantity + $quantity
            WHERE product_id = $productId";
            $conn->query($sqlUpdate);
        } else {
            // Thêm mới
            $sqlInsert = "INSERT INTO cart(product_id, quantity)
            VALUES ($productId, $quantity)";
            $conn->query($sqlInsert);
        }

        // Hiển thị thông báo
        setNotification("Product $productId added to cart", 'success');
    } else {
        echo 'Product ID not provided.';
    }

    // Đóng kết nối
    DongKetNoi($conn);
} else {
    // Trả về lỗi
    http_response_code(405);
    echo 'Method not allowed';
}
