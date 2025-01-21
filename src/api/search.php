<?php
include '../utils/db_connect.php';

// Mở kết nối
$conn = MoKetNoi();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy dữ liệu từ form
    $search = $_GET['q'];

    // Truy vấn sản phẩm
    $sql = "SELECT id, title, price, discount, headerImage
            FROM products
            WHERE title LIKE '%$search%' AND isActive = 1
            LIMIT 5";

    // Thực thi truy vấn
    $products = $conn->query($sql);

    // Đóng kết nối
    DongKetNoi($conn);

    $tabIndex = 0;
}
?>
<ul class="search-results">
    <?php foreach ($products as $product): ?>
        <li class="search-result-item">
            <a tabindex="<?= $tabIndex++ ?>"
            href="product.php?id=<?= htmlspecialchars($product['id']) ?>" 
            class="search-result-link">
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