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

    // Danh sách sản phẩm
    $productIdList = [];
    ?>

    <script>
        // Kiểm tra thông báo từ sessionStorage khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            const notification = sessionStorage.getItem('notification');

            if (notification) {
                const {
                    message,
                    type
                } = JSON.parse(notification);
                setNotification(message, type); // Gọi hàm hiển thị thông báo của bạn
                sessionStorage.removeItem('notification'); // Xóa thông báo sau khi hiển thị
            }
        });
    </script>

    <!-- Content -->
    <article class="container article">
        <!-- Menu phụ -->

        <!-- Content chính -->
        <main class="main">
            <!-- Session hot game -->
            <section class="hot-game-section">
                <h2 class="section-header hot-game-header ">
                    Featured & Recommended
                </h2>

                <div class="product hot-game">
                    <?php
                    // Kết nối CSDL
                    $conn = MoKetNoi();

                    // Truy vấn sản phẩm
                    // Lấy 8 sản phẩm có lượt xem cao nhất
                    // Không lấy sản phẩm không hoạt động
                    $sqlProducts = "SELECT * 
                            FROM products
                            WHERE isActive = 1
                            ORDER BY releaseDate DESC
                            LIMIT 8";
                    // Thực thi truy vấn
                    $products = $conn->query($sqlProducts);

                    // Thêm sản phẩm vào productIdList

                    while ($product = $products->fetch_assoc()) {
                        $productIdList[] = $product['id'];
                    }
                    ?>

                    <div class="swiper swiper-hot-game">
                        <div class="swiper-wrapper">
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                    // Kết nối CSDL
                                    $conn = MoKetNoi();

                                    // Truy vấn ảnh sản phẩm
                                    $sqlScreenshots = "SELECT * 
                                                    FROM product_screenshots
                                                    WHERE product_id = '" . $product['id'] . "'
                                                    LIMIT 4";

                                    // Thực thi truy vấn
                                    $screenshots = $conn->query($sqlScreenshots);

                                    // Truy vấn video sản phẩm
                                    $sqlVideos = "SELECT * 
                                                    FROM product_videos
                                                    WHERE product_id = '" . $product['id'] . "'
                                                    LIMIT 1";

                                    // Thực thi truy vấn
                                    $videos = $conn->query($sqlVideos);

                                    // Đóng kết nối
                                    DongKetNoi($conn);
                                    ?>

                                    <div class="swiper-slide">
                                        <div class="product-item-hot-game"
                                            data-id="<?= htmlspecialchars($product['id']) ?>">

                                            <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>" class="product-link">
                                                <!-- Image -->
                                                <div class="product-img-container">
                                                    <!-- Ảnh -->
                                                    <?php if (!empty($screenshots)): ?>
                                                        <?php foreach ($screenshots as $screenshot): ?>
                                                            <img class="product-img"
                                                                src="<?= htmlspecialchars($screenshot['screenshot']) ?>"
                                                                alt="<?= htmlspecialchars($product['title']) ?>"
                                                                loading="lazy" />
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>

                                                    <video class="product-img product-img-header active video"
                                                        loop
                                                        muted
                                                        autoplay
                                                        playsinline>
                                                        <?php if (!empty($videos)): ?>
                                                            <?php foreach ($videos as $video): ?>
                                                                <source src="<?= htmlspecialchars($video['webm']) ?>"
                                                                    type="video/webm">
                                                                <source src="<?= htmlspecialchars($video['mp4']) ?>"
                                                                    type="video/mp4">
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </video>
                                                </div>

                                                <!-- Product info -->
                                                <div class="product-info">
                                                    <!-- Title -->
                                                    <h3 class="product-title">
                                                        <?= htmlspecialchars(($product['title'])) ?>
                                                    </h3>

                                                    <!-- Screenshots -->
                                                    <div class="screenshots">
                                                        <?php if (!empty($screenshots)): ?>
                                                            <?php foreach ($screenshots as $screenshot): ?>
                                                                <img class="screenshot"
                                                                    src="<?= htmlspecialchars($screenshot['screenshot']) ?>"
                                                                    alt="<?= htmlspecialchars($product['title']) ?>"
                                                                    loading="lazy" />
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Description -->
                                                    <p class="description">
                                                        <?= htmlspecialchars($product['description']) ?>
                                                    </p>

                                                    <!-- Price -->
                                                    <div class="price-container">
                                                        <div class="price">
                                                            <span class="price-text ">
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
                                                                // Tính giá gốc
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
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="swiper-button swiper-button-next hot-game-next"></div>
                        <div class="swiper-button swiper-button-prev hot-game-prev"></div>
                    </div>
                </div>
            </section>

            <?php if (!empty($genres)): ?>
                <?php foreach ($genres as $genre): ?>
                    <?php
                    // Kết nối CSDL
                    $conn = MoKetNoi();

                    // Truy vấn sản phẩm theo thể loại, giới hạn 8 sản phẩm
                    // Không lấy sản phẩm không hoạt động
                    // Không lấy sản phẩm trong productIdList
                    $sqlProducts = "SELECT * 
                            FROM products p JOIN product_genres pg ON p.id = pg.product_id
                            WHERE pg.genre_id = '" . $genre['id'] . "' AND isActive = 1
                            AND p.id NOT IN (" . (!empty($productIdList) ? implode(",", $productIdList) : "0") . ")                            LIMIT 8";
                    // Thực thi truy vấn
                    $products = $conn->query($sqlProducts);

                    // Thêm sản phẩm vào productIdList
                    while ($product = $products->fetch_assoc()) {
                        $productIdList[] = $product['id'];
                    }
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
                            <a href="genre.php?genre=<?= htmlspecialchars($genre['name']) ?>">
                                View more
                            </a>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const productImg = document.querySelector('.product-img:not(.video)');
                    const skeletonWrappers = document.querySelectorAll('.skeleton-wrapper.product-img-skeleton');

                    // Nếu không có ảnh hoặc không có skeleton thì không cần làm gì
                    if (!productImg || skeletonWrappers.length === 0) return;

                    // Hàm cập nhật chiều cao của skeleton
                    const updateSkeletonHeight = () => {
                        const imgHeight = productImg.offsetHeight;

                        // Set chiều cao cho skeleton loader
                        skeletonWrappers.forEach(function(wrapper) {
                            wrapper.style.setProperty('--skeleton-height', `${imgHeight}px`);
                        });
                    };

                    // Cập nhật chiều cao khi hình ảnh tải xong
                    productImg.addEventListener('load', updateSkeletonHeight);

                    // Cập nhật chiều cao khi kích thước màn hình thay đổi
                    window.addEventListener('resize', updateSkeletonHeight);

                    // Nếu ảnh đã load xong thì gọi hàm onload
                    if (productImg.complete) {
                        updateSkeletonHeight();
                    }
                });
            </script>
        </main>
    </article>

    <!-- Explore catalog -->
    <section class="explore-catalog">
        <!-- Content -->
        <div class="explore-catalog-content">
            <h3 class="explore-catalog-title">
                Explore our catalogue
            </h3>
            <p class="explore-catalog-description">
                There are thousands of games waiting for you to explore. Browse by genre, features, price, and more to find your next favorite game.
            </p>
            <button class="explore-catalog-button">
                See all games
            </button>
        </div>

        <!-- Image -->
        <div class="explore-catalog-image-wrapper">
            <picture>
                <source media="(max-width: 768px)" srcset="https://images.gog.com/86843ada19050958a1aecf7de9c7403876f74d53230a5a96d7e615c1348ba6a9_explore_catalog_2560x570.webp">
                <source media="(max-width: 768px)" srcset="https://images.gog.com/86843ada19050958a1aecf7de9c7403876f74d53230a5a96d7e615c1348ba6a9_explore_catalog_2560x570.jpg">
                <img class="explore-catalog-image"
                    src="https://images.gog.com/86843ada19050958a1aecf7de9c7403876f74d53230a5a96d7e615c1348ba6a9_explore_catalog_2560x570.webp"
                    alt="Explore catalog"
                    loading="lazy">
            </picture>
        </div>
    </section>

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

            document.querySelector('.explore-catalog').addEventListener('click', function() {
                window.location.href = 'search.php';
            });
        });
    </script>

    <script src="js/index.js"></script>

    <?php
    include 'footer.php';
    ?>
</body>

</html>