<?php
include 'utils/db_connect.php';
require_once 'controller/ProductController.php';

// Mở kết nối
$conn = MoKetNoi();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy dữ liệu từ form
    $search = $_GET['q'];

    // Lấy trang hiện tại
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 8;
    $offset = ($page - 1) * $limit;

    // Lấy sort từ URL
    $sort = $_GET['sort'] ?? 'releaseDate';
    $order = $_GET['order'] ?? 'DESC';

    // Tạo câu truy vấn
    $sql = "SELECT * 
    FROM products 
    WHERE isActive = 1 AND (title LIKE '%$search%' OR description LIKE '%$search%')
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
    ";

    // Tính tổng số trang
    $countSql = "SELECT COUNT(*) as total 
        FROM products 
        WHERE title LIKE '%$search%' AND isActive = 1
    ";
    $countResult = $conn->query($countSql);
    $total = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($total / $limit);

    // Thực thi truy vấn
    $products = $conn->query($sql);

    // Khởi tạo đối tượng ProductController
    $productController = new ProductController($conn);

    // Lấy thông tin chi tiết sản phẩm theo ID
    $tables = [
        'product_screenshots',
        'product_videos',
        'product_developers',
        'product_publishers',
        'product_platforms',
        'product_genres',
        'product_tags',
        'product_features',
        'product_languages',
    ];

    // Lấy tất cả sản phẩm
    $productDetails = [];
    foreach ($products as $product) {
        $productDetails[$product['id']] = $productController->getProductDetailsById($product['id'], $tables);
    }

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

    <!-- CSS -->
    <link rel="stylesheet" href="css/search.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="components/select.css">
    <link rel="stylesheet" href="components/pagination.css">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="components/notification.js"></script>
    <script src="components/select.js"></script>
    <script src="components/pagination.js"></script>
</head>

<body>
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <script>
        var sessionCart = <?= json_encode($_SESSION['cart'] ?? []) ?>;
        var sessionUsername = <?= json_encode($_SESSION['username'] ?? '') ?>;
        var sessionRole = <?= json_encode($_SESSION['role'] ?? '') ?>;

        // Pagination
        var totalPages = <?= $totalPages ?>;
        var currentPage = <?= $page ?>;
        var sort = <?= json_encode($sort) ?>;
        var order = <?= json_encode($order) ?>;
        var search = <?= json_encode($search) ?>;
        
    </script>

    <!-- Content -->
    <article class="container">
        <!-- Content chính -->
        <main class="main">
            <!-- Search bar -->
            <section class="searchbar">
                <!-- Search bar -->
                <div class="searchbar-container">
                    <div class="searchbar-input-container">
                        <input placeholder="Search store" class="searchbar-input" type="text" value="">
                    </div>

                    <button class="searchbar-clear-btn"
                        type="button"
                        tabindex="0" type="button">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z"></path>
                        </svg>
                    </button>

                    <button class="button-searchbar" type="button">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="searchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Sort and switch display -->
                <div class="searchbar-controls">
                    <div class="sort">
                        <span class="sort-text">
                            Sort by
                        </span>

                        <!-- Select -->
                        <div id="sortSelect"></div>
                    </div>

                    <div class="switch-display">
                        <!-- Grid -->
                        <button class="switch-display-btn active" id="gridBtn">
                            <span class="switch-display-icon grid-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" id="grid" fill="currentColor">
                                    <path d="M11 10h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1zm-5 0h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1zm-5 0h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1zm10-5h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zM6 5h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zM1 5h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm10-5h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V1a1 1 0 0 1 1-1zM6 0h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V1a1 1 0 0 1 1-1zM1 0h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1a1 1 0 0 1 1-1z" />
                                </svg>
                            </span>
                        </button>

                        <!-- List -->
                        <button class="switch-display-btn" id="listBtn">
                            <span class="switch-display-icon list-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" id="list" fill="currentColor">
                                    <path d="M5 11h9v2H5v-2zm-4-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1zm4-4h9v2H5V6zM1 5h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm4-4h9v2H5V1zM1 0h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1a1 1 0 0 1 1-1z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- List of products -->
            <section class="product-container">
                <!-- Search tag -->
                <div class="search-tag-container">
                    <div class="search-tag">
                        <!-- Text -->
                        <span class="search-tag-text">
                            <span class="search-tag-title">
                                Results for
                            </span>
                            <span class="search-tag-keyword">
                                <?= htmlspecialchars($search) ?>
                            </span>
                        </span>

                        <!-- Icon -->
                        <span class="search-tag-icon">
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 48C140.559 48 48 140.559 48 256c0 115.436 92.559 208 208 208 115.435 0 208-92.564 208-208 0-115.441-92.564-208-208-208zm104.002 282.881l-29.12 29.117L256 285.117l-74.881 74.881-29.121-29.117L226.881 256l-74.883-74.881 29.121-29.116L256 226.881l74.881-74.878 29.12 29.116L285.119 256l74.883 74.881z"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <?php if ($products->num_rows === 0): ?>
                    <div class="no-result">
                        <span class="no-result-text">
                            No results found
                        </span>
                    </div>
                <?php else: ?>
                    <div class="result-count">
                        <span class="result-count-text">
                            <?= $total ?> results found
                        </span>
                    </div>

                    <!-- List of products -->
                    <ul class="product grid">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product):
                                $productDetail = $productDetails[$product['id']];
                            ?>
                                <li class="product-item" data-id="<?= htmlspecialchars($product['id']) ?>">
                                    <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>" class="product-link">
                                        <!-- Image -->
                                        <p class="product-img-container">
                                            <img class="product-img"
                                                src="<?= htmlspecialchars($product['headerImage']) ?>"
                                                alt="<?= htmlspecialchars($product['title']) ?>">
                                        </p>

                                        <!-- Info -->
                                        <div class="product-info">
                                            <!-- Title -->
                                            <h3 class="product-title">
                                                <?= htmlspecialchars($product['title']) ?>
                                            </h3>

                                            <!-- Release Date -->
                                            <span class="product-release-date">
                                                <?php
                                                // 2025-01-16 00:00:00 -> January 16, 2025
                                                $releaseDate = date('F j, Y', strtotime(htmlspecialchars($product['releaseDate'])));
                                                echo $releaseDate;
                                                ?>
                                            </span>

                                            <!-- More info -->
                                            <div class="product-more-info">
                                                <!-- Rating -->
                                                <div class="product-rating">
                                                    <!-- Star -->
                                                    <div class="product-rating-stars">
                                                        <div class="product-rating-stars-cover"></div>
                                                    </div>

                                                    <!-- Text -->
                                                    <span class="product-rating-text">
                                                        <?= htmlspecialchars($product['rating']) ?>
                                                    </span>
                                                </div>

                                                <div class="separator-dot">
                                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"></path>
                                                    </svg>
                                                </div>

                                                <!-- Platform -->
                                                <span class="product-platform">
                                                    <?php foreach ($productDetail['product_platforms'] as $platform): ?>
                                                        <img class="product-platform-icon"
                                                            src="/assets/icons/platform/<?= htmlspecialchars($platform['platform']) ?>.svg"
                                                            alt="">
                                                    <?php endforeach; ?>
                                                </span>
                                            </div>

                                            <!-- Footer -->
                                            <div class="product-footer">
                                                <!-- Wishlist -->
                                                <div class="product-wishlist">
                                                    <button class="wishlist-btn" type="button">
                                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="25px" width="25px" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20-.1-.1s0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Price -->
                                                <div class="price-container">
                                                    <!-- Cart -->
                                                    <button class="cart-btn hidden" type="button" data-id="<?= htmlspecialchars($product['id']) ?>">
                                                        <span class="cart-icon"></span>
                                                    </button>

                                                    <!-- Price -->
                                                    <div class="price">
                                                        <span class="price-text">
                                                            <?php
                                                            if ($product['price'] == 0) {
                                                                echo "Free";
                                                            } else {
                                                                echo "$" . number_format(htmlspecialchars($product['price']), 2);
                                                            }
                                                            ?>
                                                        </span>
                                                        <!-- Origin price -->
                                                        <span class="original-price">
                                                            <?php
                                                            // Tình giá gốc
                                                            $originPrice = $product['price'] / (1 - $product['discount'] / 100);
                                                            if ($product['price'] && htmlspecialchars($product['discount']) > 0):
                                                            ?>

                                                                <?= number_format($originPrice, 2) ?>
                                                            <?php endif ?>
                                                        </span>
                                                    </div>

                                                    <!-- Discount -->
                                                    <?php if ($product['price'] && htmlspecialchars($product['discount']) > 0): ?>
                                                        <span class="discount">
                                                            <?php
                                                            echo "-" .  number_format(htmlspecialchars($product['discount']), 0) . "%";
                                                            ?>
                                                        </span>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>

                <!-- Pagination controls -->
                <div class="pagination" id="pagination"></div>
            </section>
        </main>

        <script src="js/search.js"></script>
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