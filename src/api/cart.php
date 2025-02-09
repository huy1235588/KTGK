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

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

function addToCart($conn, $userId, $productId)
{
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();

        return [
            'user_id' => $userId,
            'product_id' => $productId
        ];
    }

    return null;
}

function removeFromCart($conn, $userId, $productId)
{
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

// Xử lý yêu cầu POST
if ($method === 'POST') {
    $productId = $_POST['productId'] ?? null;

    if ($productId) {
        $cart = addToCart($conn, $userId, $productId);

        if ($cart) {
            echo json_encode([
                'message' => 'Product added to cart successfully.',
                'cart' => $cart,
            ]);
        } else {
            echo json_encode([
                'message' => 'Product already exists in the cart.',
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID not provided.']);
    }
}

// Xử lý yêu cầu DELETE
else if ($method === 'DELETE') {
    $productId = $_GET['productId'] ?? null;

    if ($productId) {
        $success = removeFromCart($conn, $userId, $productId);

        if ($success) {
            echo json_encode([
                'message' => 'Product removed from cart successfully.',
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found in the cart.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID not provided.']);
    }
}

// Xử lý yêu cầu không hợp lệ
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Đóng kết nối
DongKetNoi($conn);
