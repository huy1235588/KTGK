<?php
include 'utils/db_connect.php';

$conn = MoKetNoi();

// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra sản phẩm có isAcitve = 1 không
$stmt = $conn->prepare("SELECT isActive FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

// Nếu sản phẩm không active thì chuyển hướng về trang chủ
$isActive = $result->fetch_assoc()['isActive'];
if ($isActive == 0) {
    header('Location: index.php');
    exit();
}

// Truy vấn sản phẩm 
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Lấy dữ liệu sản phẩm
    $product = $result->fetch_assoc();
    $name = htmlspecialchars($product['title']);
    $price = floatval(htmlspecialchars($product['price']));
    $discount = intval(htmlspecialchars($product['discount']));
    $description = htmlspecialchars($product['description']);

    // 2024-08-20 00:00:00 => 20 August, 2024
    $releaseDate = date('d F, Y', strtotime($product['releaseDate']));
    $discountEndDate = date('d F, Y', strtotime($product['discountEndDate']));
    $detail = $product['detail'];
} else {
    die("Sản phẩm không tồn tại.");
}


// Đóng kết nối
DongKetNoi($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name . ' Achievements' ?></title>
    <link rel="stylesheet" href="css/achievement.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="components/notification.js"></script>
</head>

<body>
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <?php

    ?>

    <!-- Content -->
    <article class="container">
        <!-- Left content -->
        <main class="main">
            <!-- Tiêu đề -->
            <h1 class="product-name">
                <?= $name ?>
            </h1>

        </main>
    </article>

    <?php
    include 'footer.php';
    ?>

</body>

</html>