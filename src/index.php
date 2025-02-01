<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
</head>

<body>
    <?php
    session_start();

    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <?php
    $conn = MoKetNoi();

    // Truy vấn danh sách genres
    $sqlGenres = "SELECT * 
    FROM genres";
    // Thực thi truy vấn
    $genres = $conn->query($sqlGenres);

    // Đóng kết nối
    DongKetNoi($conn);
    ?>

    <!-- Content -->
    <article class="container">
        <!-- Menu phụ -->
        <!-- <?php include 'sidebar.php' ?> -->

        <!-- Content chính -->
        <main class="main">
            <?php if (!empty($genres)): ?>
                <?php foreach ($genres as $genre): ?>
                    <?php
                    // Kết nối CSDL
                    $conn = MoKetNoi();

                    // Truy vấn sản phẩm theo thể loại
                    $sqlProducts = "SELECT * 
                            FROM products p JOIN product_genres pg ON p.id = pg.product_id
                            WHERE pg.genre_id = '" . $genre['id'] . "' AND isActive = 1
                            LIMIT 8";
                    // Thực thi truy vấn
                    $products = $conn->query($sqlProducts);
                    ?>
                    <section>
                        <h2 class="section-header">
                            <?= htmlspecialchars($genre['name']) ?>
                        </h2>
                        <ul class="product">
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <li class="product-item"
                                        data-id="<?= htmlspecialchars($product['product_id']) ?>">

                                        <a href="product.php?id=<?= htmlspecialchars($product['product_id']) ?>">
                                            <!-- Image -->
                                            <div class="product-img-container">
                                                <div class="skeleton-wrapper product-img-skeleton">
                                                    <!-- Skeleton loader -->
                                                    <div class="skeleton skeleton-img"></div>

                                                    <!-- Ảnh -->
                                                    <img class="product-img"
                                                        src=""
                                                        data-src="<?= htmlspecialchars($product['headerImage']) ?>"
                                                        alt="<?= htmlspecialchars($product['title']) ?>"
                                                        loading="lazy" />
                                                </div>
                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const productImg = document.querySelector('.product-img');
                                                        const skeletonWrapper = document.querySelectorAll('.skeleton-wrapper.product-img-skeleton');

                                                        // Hàm cập nhật chiều cao của skeleton
                                                        const updateSkeletonHeight = () => {
                                                            const imgHeight = productImg.offsetHeight;
                                                            
                                                            // Set chiều cao cho skeleton loader
                                                            skeletonWrapper.forEach(function(wrapper) {
                                                                wrapper.style.setProperty('--skeleton-height', `${imgHeight}px`);
                                                            });
                                                        };

                                                        // Cập nhật chiều cao khi hình ảnh tải xong
                                                        // productImg.onload = updateSkeletonHeight;

                                                        // Cập nhật chiều cao khi kích thước màn hình thay đổi
                                                        window.addEventListener('resize', updateSkeletonHeight);

                                                        // Nếu ảnh đã load xong thì gọi hàm onload
                                                        if (productImg.complete) {
                                                            productImg.onload();
                                                        }
                                                    });
                                                </script>
                                            </div>
                                            <!-- Product info -->
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
                        <div class="view-more">
                            <a href="genre.php?genre=<?= htmlspecialchars($genre['name']) ?>">Xem thêm</a>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </article>

    <!-- Game hover -->
    <div class="product-hover">
        <div class="product-hover-content"></div>
        <div class="arrow-left"></div>
        <div class="arrow-right"></div>
    </div>
    <script src="js/gameHover.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new GameHover(".product-item", ".product-hover");
        });
    </script>

    <?php
    include 'footer.php';
    ?>
</body>

</html>