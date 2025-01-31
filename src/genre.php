<?php
include 'utils/db_connect.php';

$conn = MoKetNoi();

// Lấy thể loại từ URL
$genre_id = $_GET['genre'];

// Lấy trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Truy vấn danh sách sản phẩm theo thể loại
$sqlProducts = "SELECT *
    FROM products p JOIN product_genres pg ON p.id = pg.product_id
                    JOIN genres g ON pg.genre_id = g.id
    WHERE g.name = '$genre_id' AND p.isActive = 1
    LIMIT $limit OFFSET $offset
";

// Thực thi truy vấn
$products = $conn->query($sqlProducts);

$countSql = "SELECT COUNT(*) as total 
    FROM products p JOIN product_genres pg ON p.id = pg.product_id
                    JOIN genres g ON pg.genre_id = g.id
    WHERE g.name = '$genre_id' AND p.isActive = 1
";

// Thực thi truy vấn
$countResult = $conn->query($countSql);
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$genre_name = $products->fetch_assoc()['name'];

// Đóng kết nối
DongKetNoi($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($genre_name) ?>
    </title>
    <!-- Css -->
    <link rel="stylesheet" href="css/genre.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="components/pagination.css">

    <!-- JS -->
    <script src="components/pagination.js"></script>
</head>

<body>
    <?php
    session_start();

    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <!-- Content -->
    <article class="container">
        <!-- Content chính -->
        <main class="main">
            <section class="product-container">
                <h2 class="genre-title">
                    <?= htmlspecialchars($genre_name) ?>
                </h2>

                <ul class="product">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <li class="product-item">
                                <a href="product.php?id=<?= htmlspecialchars($product['product_id']) ?>">
                                    <!-- Image -->
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($product['headerImage']) ?>"
                                            alt="<?= htmlspecialchars($product['title']) ?>">
                                    </p>
                                    <!-- Info -->
                                    <div class="product-info">
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
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <!-- Pagination controls -->
                <div class="pagination" id="pagination"></div>
            </section>
        </main>
    </article>

    <script>
        // Hàm xử lý sự kiện khi chuyển trang
        function changePage() {
            new Pagination({
                container: '#pagination',
                totalPages: totalPages,
                currentPage: currentPage,
                onPageChange: (page) => {
                    goToPage(page);
                }
            });

            // Hàm xử lý khi chuyển trang
            function goToPage(page) {
                // Tạo một URL mới từ đường dẫn hiện tại
                const url = new URL(window.location.href);

                // Cập nhật tham số `page` trên URL
                url.searchParams.set('page', page);

                // Điều hướng đến URL mới
                window.location.href = url.toString(); // Sử dụng `toString` để chuyển đổi URL thành chuỗi
            }
        }

        // Lấy trang hiện tại từ URL
        const currentPage = <?= $page ?>;

        // Tổng số trang
        const totalPages = <?= $totalPages ?>;
        changePage();
    </script>

    <?php
    include 'footer.php';
    ?>
</body>

</html>