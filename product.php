<!DOCTYPE html>
<html lang="en">

<?php
include 'utils/db_connect.php';
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
    $name = htmlspecialchars($product['name']);
    $price = floatval(htmlspecialchars($product['price']));
    $original_price = floatval(htmlspecialchars($product['original_price']));
    $description = htmlspecialchars($product['description']);
    $image = htmlspecialchars($product['image']);
} else {
    die("Sản phẩm không tồn tại.");
}

// Truy vấn danh sách sản phẩm Táo
$sqlTao = "SELECT * FROM products WHERE name like 'Táo%' LIMIT 8";
$sanPhamTao = $conn->query($sqlTao);

// Đóng kết nối
$stmt->close();
DongKetNoi($conn);
?>

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
    $folder = $image;
    $imageFiles = array_filter(scandir($folder), function ($file) use ($folder) {
        return is_file($folder . '/' . $file)
            && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)
            && $file !== 'anh_bia.jpg'; // Loại trừ file header.jpg    
    });
    ?>

    <!-- Content -->
    <article class="container">
        <!-- Left content -->
        <main class="main">
            <!-- Image -->
            <section class="img-container">
                <div class="swiper product-img">
                    <ul class="swiper-wrapper">
                        <?php foreach ($imageFiles as $img): ?>
                            <li class="swiper-slide">
                                <img src="<?= $image . '/' . $img ?>" alt="<?= $name ?>">
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
                        <?php foreach ($imageFiles as $img): ?>
                            <li class="swiper-slide">
                                <img src="<?= $image . '/' . $img ?>" alt="<?= htmlspecialchars($name) ?>" class="thumb-img">
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

            </section>

            <section class="section product-relate-container">
                <h2 class="title">
                    Sẩn phẩm liên quan
                </h2>

                <div class="swiper product-relate">
                    <ul class="swiper-wrapper">
                        <?php foreach ($sanPhamTao as $tao): ?>
                            <li class="swiper-slide">
                                <a href="product.php?id=<?= htmlspecialchars($tao['id']) ?>">
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($tao['image']) ?>/anh_bia.jpg"
                                            alt="<?= htmlspecialchars($tao['name']) ?>">
                                    </p>
                                    <p class="product-name">
                                        <?= htmlspecialchars(($tao['name'])) ?>
                                    </p>
                                    <!-- Price -->
                                    <div class="price-container">
                                        <span class="price">
                                            <?php
                                            if ($tao['price'] != null) {
                                                echo number_format($tao['price'], 3) . "₫";
                                            } else {
                                                echo number_format(htmlspecialchars($tao['original_price']), 3) . "₫";
                                            }
                                            ?>
                                        </span>

                                        <!-- Origin price -->
                                        <?php if ($tao['price'] && htmlspecialchars($tao['original_price']) > 0): ?>
                                            <p class="origin-price">
                                                <span class="original-price">
                                                    <?= number_format(htmlspecialchars($tao['original_price']), 3) ?>
                                                </span>
                                                <span class="discount">
                                                    <?php
                                                    // Calculate the discount percentage
                                                    $discount = (($tao['original_price'] - $tao['price']) / $product['original_price']) * 100;
                                                    echo "-" . number_format($discount, 0) . '%';
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
                <?php if ($price && $original_price > 0): ?>
                    <p class="origin-price">
                        <span class="original-price">
                            <?= number_format($original_price, 3) . "đ" ?>
                        </span>
                        <span class="discount">
                            <?php
                            // Tính phần trăm giảm giá
                            $discount = (($original_price - $price) / $original_price) * 100;
                            echo "-" . number_format($discount, 0) . '%';
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