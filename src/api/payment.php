<?php
include '../utils/db_connect.php';
$conn = MoKetNoi();

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    die('You must login to purchase');
}

// Xử lý thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];
    $paymentMethod = $_POST['paymentMethodForm'] ?? 'unknown';

    // Lấy giỏ hàng từ session
    $cartItems = $_SESSION['cart'] ?? [];

    // Tính tổng tiền
    $total = $_SESSION['total'] ?? 0;

    // Kiểm tra giỏ hàng
    if (empty($cartItems) || $total < 0) {
        die('Invalid cart contents');
    }

    try {
        // Bắt đầu transaction
        mysqli_begin_transaction($conn);

        $status = 'completed';

        // Tạo hoá đơn
        $stmt = $conn->prepare("INSERT INTO orders (user_id, totalAmount, paymentMethod, status) 
                                VALUES (?, ?, ?, ?)");
                         echo $paymentMethod;
        $stmt->bind_param("idss", $userId, $total, $paymentMethod, $status);
        $stmt->execute();
        $OrderID = $stmt->insert_id;

        // Thêm items vào hoá đơn và thư viện
        foreach ($cartItems as $item) {            
            // Thêm vào order_details
            $stmt = $conn->prepare("INSERT INTO order_details
                             (order_id, product_id, total)
                             VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $OrderID, $item['productId'], $item['price']);
            $stmt->execute();

            // Thêm vào library
            $stmt = $conn->prepare("INSERT INTO library (user_id, product_id)
                             VALUES (?, ?)
                             ON DUPLICATE KEY UPDATE purchased_at = NOW()");
            $stmt->bind_param("ii", $userId, $item['productId']);
            $stmt->execute();
        }

        // Commit transaction
        mysqli_commit($conn);

        // Xoá giỏ hàng
        unset($_SESSION['cart']);
        unset($_SESSION['total']);
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Chuyển hướng về thư viện
        header('Location: /library.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Payment failed: " . $e->getMessage());
    }
}
