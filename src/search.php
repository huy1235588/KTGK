<?php
include 'utils/db_connect.php';
require_once 'controller/ProductController.php';

// Mở kết nối
$conn = MoKetNoi();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy dữ liệu từ form
    $search = $_GET['q'] ?? '';

    // Lấy trang hiện tại
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;

    // Lấy sort từ URL
    $sort = $_GET['sort'] ?? 'releaseDate';
    $order = $_GET['order'] ?? 'DESC';

    // Lấy giá trị từ URL
    $min_price = $_GET['min_price'] ?? 0;
    $max_price = $_GET['max_price'] ?? 999999;
    $min_rating = $_GET['min_rating'] ?? 0;
    $min_discount = $_GET['min_discount'] ?? 0;
    $min_release = $_GET['min_release'] ?? '1970-01-01';
    $max_release = $_GET['max_release'] ?? '2100-12-31';

    // Tạo câu truy vấn
    $sql = "SELECT * 
    FROM products 
    WHERE isActive = 1 AND 
    (title LIKE '%$search%' OR description LIKE '%$search%')
    AND price >= $min_price AND price <= $max_price
    AND rating >= $min_rating
    AND discount >= $min_discount
    AND releaseDate >= '$min_release' AND releaseDate <= '$max_release'
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
    ";

    // Thực thi truy vấn
    $products = $conn->query($sql);

    // Tính tổng số trang
    $countSql = "SELECT COUNT(*) as total 
        FROM products 
        WHERE isActive = 1 AND
        (title LIKE '%$search%' OR description LIKE '%$search%')
        AND price >= $min_price AND price <= $max_price
        AND rating >= $min_rating
        AND discount >= $min_discount
        AND releaseDate >= '$min_release' AND releaseDate <= '$max_release'
    ";

    // Thực thi truy vấn
    $countResult = $conn->query($countSql);
    $total = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($total / $limit);

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

    // Lấy thông tin giỏ hàng từ database
    $stmt = $conn->prepare("SELECT product_id FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user']['id']);
    $stmt->execute();
    $cartResult = $stmt->get_result();
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
    <script src="utils/js/jquery.js"></script>
    <script src="utils/js/dataTable.js"></script>
</head>

<body>
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <script>
        var sessionCart = <?= json_encode($cartResult->fetch_all(MYSQLI_ASSOC)) ?>;
        var sessionUsername = <?= json_encode($_SESSION['user']['username'] ?? '') ?>;
        var sessionRole = <?= json_encode($_SESSION['user']['role'] ?? '') ?>;

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
                                                    <?php foreach ($productDetail['product_platforms'] as $platform):

                                                    ?>
                                                        <img class="product-platform-icon"
                                                            src="/assets/icons/platform/<?= htmlspecialchars($platform['name']) ?>.svg"
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
                                                                $<?= number_format($originPrice, 2) ?>
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

            <!-- Filter -->
            <aside class="filter-container">
                <h2 class="filter-title">
                    Filter
                </h2>

                <form method="GET" class="filters" id="js-filters">
                    <!-- Price -->
                    <div class="filter-body price-filter">
                        <div class="fancy-price" aria-label="Filter by minimum and maximum price in selected currency">
                            Price
                            <input class="fancy-price-input" type="text" inputmode="numeric" name="min_price" maxlength="8" pattern="[0-9]+(\.[0-9]+)?" title="Only numbers (with or without fractions)" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?= htmlspecialchars($_GET['min_price'] ?? 0) ?>" aria-label="Minimum price">
                            to
                            <input class="fancy-price-input" type="text" inputmode="numeric" name="max_price" maxlength="8" pattern="[0-9]+(\.[0-9]+)?" title="Only numbers (with or without fractions)" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>" aria-label="Maximum price" placeholder="∞">
                        </div>

                        <div class="block_rule"></div>

                        <label class="filter-checkbox">
                            <input type="checkbox" name="category">
                            <span class="filter-checkbox-text">
                                Special Offers
                            </span>
                            <span class="filter-exclude-checkbox"></span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="category">
                            <span class="filter-checkbox-text">
                                Hide free to play items
                            </span>
                            <span class="filter-exclude-checkbox"></span>
                        </label>
                    </div>

                    <!-- Rating -->
                    <div class="fancy-range">
                        <label for="js-input-rating">Rating: ≥<span id="js-value-rating">0</span></label>
                        <input type="range" class="fancy-range-input" id="js-input-rating" name="min_rating" min="0" max="5" step="0.1" value="<?= htmlspecialchars($_GET['min_rating'] ?? 0) ?>">
                    </div>

                    <!-- Discount -->
                    <div class="fancy-range">
                        <label for="js-input-discount">Discount: <span id="js-value-discount"><?php
                                                                                                if (isset($_GET['min_discount']) && $_GET['min_discount'] > 0) {
                                                                                                    echo "≥";
                                                                                                } else {
                                                                                                    echo ">";
                                                                                                }
                                                                                                echo htmlspecialchars($_GET['min_discount'] ?? 0);
                                                                                                ?></span>%</label>
                        <input type="range"
                            class="fancy-range-input"
                            id="js-input-discount"
                            name="min_discount"
                            min="0"
                            max="95"
                            step="5"
                            value="<?= htmlspecialchars($_GET['min_discount'] ?? 0) ?>">
                    </div>

                    <!-- Release date -->
                    <div class="fancy-date-container">
                        <div class="fancy-date-title">Release date</div>
                        <label for="js-input-release-min">From</label>
                        <input type="date" name="min_release" value="<?= htmlspecialchars($_GET['min_release'] ?? '') ?>" min="1970-01-01" max="2100-12-31" class="fancy-date-input" id="js-input-release-min">
                        <label for="js-input-release-max">To</label>
                        <input type="date" name="max_release" value="<?= htmlspecialchars($_GET['max_release'] ?? '') ?>" min="1970-01-01" max="2100-12-31" class="fancy-date-input" id="js-input-release-max">
                    </div>

                    <!-- Platform -->
                    <div class="filter-select" id="js-select-platform">
                        <!-- header -->
                        <div class="filter-header">
                            <div class="filter-header-title">
                                Platform
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="filter-body">
                            <?php foreach ($productController->getPlatforms() as $platform):
                                $platforms = $productController->getPlatforms();
                                $isChecked = isset($_GET['platform']) && $platform['name'] === $_GET['platform'];
                            ?>
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="platform" value="<?= htmlspecialchars($platform['name']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                    <span>
                                        <?= htmlspecialchars($platform['name']) ?>
                                    </span>
                                    <span class="filter-exclude-checkbox"></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Genre -->
                    <div class="filter-select" id="js-select-genre">
                        <!-- Header -->
                        <div class="filter-header">
                            <div class="filter-header-title">
                                Genre
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="filter-body">
                            <!-- Search -->
                            <div class="filter-select-search">
                                <input type="search"
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitilize="off"
                                    spellcheck="false"
                                    placeholder="Search">
                            </div>

                            <!-- Scrollable -->
                            <div class="filter-scrollable">
                                <?php foreach ($productController->getGenres() as $genre):
                                    // 1%2C4%2C7 -> [1, 4, 7]
                                    $genresChecked = isset($_GET['genre']) ? explode(',', $_GET['genre']) : [];
                                    $isChecked = isset($_GET['genre']) && in_array($genre['id'], $genresChecked);
                                ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="category" value="<?= htmlspecialchars($genre['id']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <span class="filter-checkbox-text">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </span>
                                        <span class="filter-exclude-checkbox"></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="filter-select" id="js-select-tag">
                        <!-- Header -->
                        <div class="filter-header">
                            <div class="filter-header-title">
                                Tags
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="filter-body">
                            <!-- Search -->
                            <div class="filter-select-search">
                                <input type="search"
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitilize="off"
                                    spellcheck="false"
                                    placeholder="Search">
                            </div>

                            <!-- Scrollable -->
                            <div class="filter-scrollable">
                                <?php foreach ($productController->getTags() as $tag):
                                    $tagChecked = isset($_GET['tag']) ? explode(',', $_GET['tag']) : [];
                                    $isChecked = isset($_GET['tag']) && in_array($tag['id'], $tagChecked);
                                ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="category" value="<?= htmlspecialchars($tag['id']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <span class="filter-checkbox-text">
                                            <?= htmlspecialchars($tag['name']) ?>
                                            (<?= htmlspecialchars($tag['count']) ?>)
                                        </span>
                                        <span class="filter-exclude-checkbox"></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="filter-select" id="js-select-feature">
                        <!-- Header -->
                        <div class="filter-header">
                            <div class="filter-header-title">
                                Features
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="filter-body">
                            <!-- Search -->
                            <div class="filter-select-search">
                                <input type="search"
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitilize="off"
                                    spellcheck="false"
                                    placeholder="Search">
                            </div>

                            <!-- Scrollable -->
                            <div class="filter-scrollable">
                                <?php foreach ($productController->getFeatures() as $feature):
                                    $featureChecked = isset($_GET['feature']) ? explode(',', $_GET['feature']) : [];
                                    $isChecked = isset($_GET['feature']) && in_array($feature['id'], $featureChecked);
                                ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="category" value="<?= htmlspecialchars($feature['id']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <span class="filter-checkbox-text">
                                            <?= htmlspecialchars($feature['name']) ?>
                                        </span>
                                        <span class="filter-exclude-checkbox"></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Language -->
                    <div class="filter-select" id="js-select-language">
                        <!-- Header -->
                        <div class="filter-header">
                            <div class="filter-header-title">
                                Language
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="filter-body">
                            <!-- Search -->
                            <div class="filter-select-search">
                                <input type="search"
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitilize="off"
                                    spellcheck="false"
                                    placeholder="Search">
                            </div>

                            <!-- Scrollable -->
                            <div class="filter-scrollable">
                                <?php foreach ($productController->getLanguages() as $language):
                                    $languageChecked = isset($_GET['language']) ? explode(',', $_GET['language']) : [];
                                    $isChecked = isset($_GET['language']) && in_array($language['id'], $languageChecked);
                                ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="category" value="<?= htmlspecialchars($language['id']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <span class="filter-checkbox-text">
                                            <?= htmlspecialchars($language['name']) ?>
                                        </span>
                                        <span class="filter-exclude-checkbox"></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Button Submit -->
                    <div class="filter-submit-wrap">
                        <button id="js-filter-submit" class="btn" type="submit">
                            Apply new filters
                        </button>
                    </div>

                    <!-- Button Reset -->
                    <a href="/search.php?q=<?php echo htmlspecialchars($search) ?>"
                        class="btn btn-link hide-small" id="js-filters-reset">
                        Clear filters
                    </a>
                </form>

            </aside>
        </main>

        <script src=" js/search.js">
        </script>
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
    // Đóng kết nối
    // DongKetNoi($conn);
    ?>

</body>

</html>