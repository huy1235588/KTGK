<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name ?></title>
    <link rel="stylesheet" href="css/product.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body>
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <?php
    $conn = MoKetNoi();

    // Lấy ID sản phẩm từ URL
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
    } else {
        die("Sản phẩm không tồn tại.");
    }

    // Truy vấn danh sách screenshot
    $stmt = $conn->prepare("SELECT * FROM product_screenshots WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách screenshot
    $screenshot = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $screenshot[] = $row['screenshot'];
        }
    }

    // Lấy genre của sản phẩm
    $stmt = $conn->prepare("SELECT genre FROM product_genres WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $genre = $result->fetch_assoc();
    }

    // Truy vấn sản phẩm cùng genres
    // $stmt = $conn->prepare("SELECT * FROM products p JOIN product_genres pg ON p.id = pg.product_id WHERE pg.genre = ? AND p.id != ? LIMIT 4");
    // $stmt->bind_param("si", $genre, $productId);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // Lấy danh sách sản phẩm cùng genres
    $sanPhamGenre = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sanPhamGenre[] = $row;
        }
    }

    // Đóng kết nối
    $stmt->close();
    DongKetNoi($conn);
    ?>

    <!-- Content -->
    <article class="container">
        <!-- Left content -->
        <main class="main">
            <!-- Image -->
            <section class="img-container">
                <div class="swiper product-img">
                    <ul class="swiper-wrapper">
                        <?php foreach ($screenshot as $img): ?>
                            <li class="swiper-slide">
                                <img src="<?= $img ?>" alt="<?= $name ?>">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Nút điều hướng -->
                    <div class="swiper-button swiper-button-prev"></div>
                    <div class="swiper-button swiper-button-next"></div>
                </div>

                <!-- Swiper thu nhỏ bên dưới -->
                <div class="swiper swiper-thumbs">
                    <ul class="swiper-wrapper">
                        <?php foreach ($screenshot as $img): ?>
                            <li class="swiper-slide">
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($name) ?>" class="thumb-img">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>

            <!-- Thông tin chi tiết -->
            <section class="section product-info">
                <h2 class="title">
                    Thông tin sản phẩm
                </h2>
                <p class="description">
                    <?= $description ?>
                </p>

            </section>

            <section class="section product-relate-container">
                <h2 class="title">
                    Sẩn phẩm liên quan
                </h2>

                <div class="swiper product-relate">
                    <ul class="swiper-wrapper">
                        <?php foreach ($sanPhamGenre as $sp): ?>
                            <li class="swiper-slide">
                                <a href="product.php?id=<?= htmlspecialchars($sp['id']) ?>">
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($sp['headerImage']) ?>/anh_bia.jpg"
                                            alt="<?= htmlspecialchars($sp['title']) ?>">
                                    </p>
                                    <p class="product-name">
                                        <?= htmlspecialchars(($sp['title'])) ?>
                                    </p>
                                    <!-- Price -->
                                    <div class="price-container">
                                        <span class="price">
                                            <?php
                                            if ($sp['price'] != null) {
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
                    </ul>
                    <!-- Nút điều hướng
                    <div class="swiper-button product-relate-button-prev"></div>
                    <div class="swiper-button product-relate-button-next"></div> -->
                </div>
            </section>
        </main>

        <!-- Right content -->
        <aside class="right-content">
            <h1 class="product-name">
                <?= $name ?>
            </h1>
            <!-- Price -->
            <div class="price-container">
                <span class="price">
                    <?php
                    if ($product['price'] != null) {
                        echo number_format($price, 3) . "₫";
                    } else {
                        echo number_format($original_price, 3) . "₫";
                    }
                    ?>
                </span>

                <!-- Origin price -->
                <?php if ($price && $discount > 0): ?>
                    <p class="origin-price">
                        <span class="original-price">
                            <?php
                            // Tình giá gốc
                            $originPrice = $price / (1 - $discount / 100);
                            ?>

                            <?= number_format($discount, 3) . "₫" ?>
                        </span>
                        <span class="discount">
                            <?php
                            echo "-" .  number_format(htmlspecialchars($product['discount']), 0) . "%";
                            ?>
                        </span>
                    </p>
                <?php endif ?>
            </div>

            <!-- Số lượng -->
            <div class="quantity-container">
                <label class="quantity-label" for="quantity">Số lượng:</label>
                <div>
                    <button id="decrement">
                        -
                    </button>
                    <input type="number" id="quantity" value="20" min="1" max="100">
                    <button id="increment">
                        +
                    </button>
                </div>
            </div>

            <!-- Nút mua -->
            <button class="buy-btn">
                MUA NGAY
            </button>

            <p class="refund">
                ĂN KHÔNG NGON, 1 ĐỔI 1
            </p>

            <div class="product-policises-container">
                <h5 class="m-0 mb-3">
                    Tiêu chuẩn dịch vụ
                </h5>
                <ul class="product-policises">
                    <li>
                        <img src="//theme.hstatic.net/1000141988/1001239110/14/policy_product_image_1.png?v=374">
                        <p class="product-policises-text">
                            Giao hàng nội thành 2 - 4 giờ
                        </p>
                    </li>
                    <li>
                        <img src="https://theme.hstatic.net/1000141988/1001239110/14/policy_product_image_3.png?v=374">
                        <p class="product-policises-text">
                            Đổi trả trong 48 giờ nếu sản phẩm không đạt chất lượng cam kết
                        </p>
                    </li>
                </ul>
            </div>
        </aside>
    </article>

    <?php
    include 'footer.php';
    ?>

    <script src="js/product.js"></script>
</body>

</html>