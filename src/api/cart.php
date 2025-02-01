<?php
include '../utils/db_connect.php';
include '../components/notification.php';

// Mở kết nối
$conn = MoKetNoi();

// Xử lý yêu cầu
$method = $_SERVER['REQUEST_METHOD'];

// Set Content-Type
header('Content-Type: application/json');

// Lấy thông tin user
$userId = $_SESSION['user']['id'] ?? null;

// Nếu là POST
if ($method === 'POST') {
    // Lấy thông tin sản phẩm
    $productId = $_POST['productId'] ?? null;

    if (isset($productId)) {
        // Nếu chưa có giỏ hàng thì tạo mới
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
            // Thêm sản phẩm vào SESSION['cart']
            array_push($_SESSION['cart'], [
                'productId' => $productId,
            ]);

            // Thêm vào database
            $sql = "INSERT INTO cart (user_id, product_id) 
            VALUES ('$userId', '$productId')";
            mysqli_query($conn, $sql);
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
        // Lấy giỏ hàng từ database
        $sql = "SELECT * 
            FROM cart
            WHERE user_id = {$_SESSION['user']['id']}";
        $result = mysqli_query($conn, $sql);
        $carts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $isExist = false;
        $index = -1;
        foreach ($carts as $i => $item) {
            if ($item['product_id'] === $productId) {
                $isExist = true;
                $index = $i;
                break;
            }
        }

        // Nếu sản phẩm đã tồn tại trong giỏ hàng
        if ($isExist) {
            // Xoá sản phẩm khỏi SESSION['cart']
            array_splice($_SESSION['cart'], $index, 1);

            // Xoá sản phẩm khỏi database
            $sql = "DELETE FROM cart 
            WHERE user_id = '$userId' 
            AND product_id = '$productId'";

            $result = mysqli_query($conn, $sql);
        }

        // Trả về json
        echo json_encode(array(
            'message' => 'Product removed from cart successfully.',
            'cart' => $_SESSION['cart'],
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
