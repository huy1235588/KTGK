<?php
include 'utils/db_connect.php';

// Mở kết nối
$conn = MoKetNoi();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// Lấy dữ liệu từ form
$search = $_GET['q'];

// Truy vấn sản phẩm
$sql = "SELECT id, title, price, discount, headerImage
FROM products
WHERE title LIKE '%$search%'
LIMIT 5";

// Thực thi truy vấn
$products = $conn->query($sql);

// Đóng kết nối
DongKetNoi($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/product.css">
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

    <!-- Main -->
    <main>
        <ul class="search-results">
            <?php foreach ($products as $product): ?>
                <li class="search-result-item">
                    <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>" class="search-result-link">
                        <img src="<?= htmlspecialchars($product['headerImage']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" />
                        <p class="search-title">
                            <?= htmlspecialchars($product['title']) ?>
                        </p>
                        <p class="search-price">
                            <?php if ($product['price'] == 0): ?>
                                Free
                            <?php else: ?>
                                <?= htmlspecialchars(number_format($product['price'], 2)) ?>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>

</html>