<?php
include 'utils/db_connect.php';

// Mở kết nối
$conn = MoKetNoi();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy dữ liệu từ form
    $search = $_GET['q'];

    // Add pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 8;
    $offset = ($page - 1) * $limit;

    // Modify SQL query
    $sql = "SELECT * FROM products WHERE title LIKE '%$search%' LIMIT $limit OFFSET $offset";

    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM products WHERE title LIKE '%$search%'";
    $countResult = $conn->query($countSql);
    $total = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($total / $limit);

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
    <link rel="stylesheet" href="css/search.css">
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

    <!-- Content -->
    <article class="container">
        <!-- Content chính -->
        <main class="main">
            <section>
                <!-- Search and Sort -->
               
                <ul class="product">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <li class="product-item" data-id="<?= htmlspecialchars($product['id']) ?>">
                                <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($product['headerImage']) ?>"
                                            alt="<?= htmlspecialchars($product['title']) ?>">
                                    </p>
                                    <h3 class="product-title">
                                        <?= htmlspecialchars(($product['title'])) ?>
                                    </h3>
                                    <!-- Price -->
                                    <div class="price-container">
                                        <span class="price">
                                            <?php
                                            if ($product['price'] != null) {
                                                echo "$" . number_format($product['price'], 2);
                                            } else {
                                                echo "$" . number_format(htmlspecialchars($product['price']), 2);
                                            }
                                            ?>
                                        </span>

                                        <!-- Origin price -->
                                        <?php if ($product['price'] && htmlspecialchars($product['discount']) > 0): ?>
                                            <p class="origin-price">
                                                <span class="original-price">
                                                    <?php
                                                    // Tình giá gốc
                                                    $originPrice = $product['price'] / (1 - $product['discount'] / 100);
                                                    ?>

                                                    <?= number_format($originPrice, 2) ?>
                                                </span>
                                                <span class="discount">
                                                    <?php
                                                    echo "-" .  number_format(htmlspecialchars($product['discount']), 0) . "%";
                                                    ?>
                                                </span>
                                            </p>
                                        <?php endif ?>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <!-- Pagination controls -->
                <div class="pagination">
                    <?php
                    $range = 2;
                    if ($totalPages > 1) {
                        // Previous button
                        if ($page > 1) {
                            echo '<a href="?q=' . urlencode($search) . '&page=' . ($page - 1) . '" class="prev">
                                <
                            </a>';
                        } else {
                            echo '<span class="disabled" class="prev">
                                <
                            </span>';
                        }

                        // First page
                        if ($page > ($range + 1)) {
                            echo '<a href="?q=' . urlencode($search) . '&page=1">1</a>';
                            echo '<span>...</span>';
                        }

                        // Page range
                        for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++) {
                            if ($i == $page) {
                                echo '<a class="active" href="#">' . $i . '</a>';
                            } else {
                                echo '<a href="?q=' . urlencode($search) . '&page=' . $i . '">' . $i . '</a>';
                            }
                        }

                        // Last page
                        if ($page < ($totalPages - $range)) {
                            echo '<span>...</span>';
                            echo '<a href="?q=' . urlencode($search) . '&page=' . $totalPages . '">' . $totalPages . '</a>';
                        }

                        // Next button
                        if ($page < $totalPages) {
                            echo '<a href="?q=' . urlencode($search) . '&page=' . ($page + 1) . '" class="next">
                                >
                            </a>';
                        } else {
                            echo '<span class="disabled" class="next">
                                >
                            </span>';
                        }
                    }
                    ?>
                </div>
            </section>
        </main>
    </article>

    <!-- Game hover -->
    <div class="product-hover">
        <div class="product-hover-content"></div>
        <div class="arrow-left"></div>
        <div class="arrow-right"></div>
    </div>
    <script src="js/gameHover.js"></script>

    <?php
    include 'footer.php';
    ?>

</body>

</html>