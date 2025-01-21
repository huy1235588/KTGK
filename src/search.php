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
    $sql = "SELECT * 
    FROM products 
    WHERE isActive = 1 AND (title LIKE '%$search%' OR description LIKE '%$search%')
    LIMIT $limit OFFSET $offset";

    // Get total count
    $countSql = "SELECT COUNT(*) as total 
        FROM products 
        WHERE title LIKE '%$search%' AND isActive = 1
    ";
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
                        <select class="sort-select">
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                        </select>
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
                            <?php foreach ($products as $product): ?>
                                <li class="product-item" data-id="<?= htmlspecialchars($product['id']) ?>">
                                    <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>">
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
                                                <?= htmlspecialchars(($product['title'])) ?>
                                            </h3>
                                            <!-- Price -->
                                            <div class="price-container">
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
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>

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

        <script src="js/search.js"></script>
    </article>

    <!-- Game hover -->
    <div class="product-hover">
        <div class="product-hover-content"></div>
        <div class="arrow-left"></div>
        <div class="arrow-right"></div>
    </div>
    <script src="js/gameHover.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new GameHover('.product-item', '.product-hover');
        });
    </script>

    <?php
    include 'footer.php';
    ?>

</body>

</html>