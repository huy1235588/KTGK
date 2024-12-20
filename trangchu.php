<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="css/trangchu.css">
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
    include 'utils/db_connect.php';
    $conn = MoKetNoi();

    // Truy vấn danh sách sản phẩm Táo
    $sqlTao = "SELECT * FROM products WHERE name like 'Táo%' LIMIT 8";
    $sanPhamTao = $conn->query($sqlTao);

    $sqlQua = "SELECT * FROM products WHERE name like '%trái cây%' LIMIT 8";
    $sanPhamQua = $conn->query($sqlQua);

    $sqlChuoi = "SELECT * FROM products WHERE name like 'Chuối%' LIMIT 8";
    $sanPhamChuoi = $conn->query($sqlChuoi);

    $sqlCam = "SELECT * FROM products WHERE name like 'Cam%' LIMIT 8";
    $sanPhamCam = $conn->query($sqlCam);

    // Đóng kết nối
    DongKetNoi($conn);
    ?>

    <!-- Content -->
    <article class="container">
        <!-- Menu phụ -->
        <?php include 'sidebar.php' ?>

        <!-- Content chính -->
        <main class="main">
            <!-- Táo -->
            <section>
                <h2 class="section-header">Táo</h2>
                <ul class="product">
                    <?php if (!empty($sanPhamTao)): ?>
                        <?php foreach ($sanPhamTao as $product): ?>
                            <li class="product-item">
                                <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($product['image']) ?>/anh_bia.jpg"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                    </p>
                                    <p class="product-title">
                                        <?= htmlspecialchars(($product['name'])) ?>
                                    </p>
                                    <!-- Price -->
                                    <div class="price-container">
                                        <span class="price">
                                            <?php
                                            if ($product['price'] != null) {
                                                echo number_format($product['price'], 3) . "₫";
                                            } else {
                                                echo number_format(htmlspecialchars($product['original_price']), 3) . "₫";
                                            }
                                            ?>
                                        </span>

                                        <!-- Origin price -->
                                        <?php if ($product['price'] && htmlspecialchars($product['original_price']) > 0): ?>
                                            <p class="origin-price">
                                                <span class="original-price">
                                                    <?= number_format(htmlspecialchars($product['original_price']), 3) ?>
                                                </span>
                                                <span class="discount">
                                                    <?php
                                                    // Calculate the discount percentage
                                                    $discount = (($product['original_price'] - $product['price']) / $product['original_price']) * 100;
                                                    echo "-" . number_format($discount, 0) . '%';
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
            </section>

            <!-- Chuối -->
            <section>
                <h2 class="section-header">QUÀ TẾT GIÁ TỐT</h2>
                <ul class="product">
                    <?php if (!empty($sanPhamTao)): ?>
                        <?php foreach ($sanPhamQua as $product): ?>
                            <li class="product-item">
                                <a href="product.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($product['image']) ?>/anh_bia.jpg"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                    </p>
                                    <p class="product-title">
                                        <?= htmlspecialchars(($product['name'])) ?>
                                    </p>
                                    <!-- Price -->
                                    <div class="price-container">
                                        <span class="price">
                                            <?php
                                            if ($product['price'] != null) {
                                                echo number_format($product['price'], 3) . "₫";
                                            } else {
                                                echo number_format(htmlspecialchars($product['original_price']), 3) . "₫";
                                            }
                                            ?>
                                        </span>

                                        <!-- Origin price -->
                                        <?php if ($product['price'] && htmlspecialchars($product['original_price']) > 0): ?>
                                            <p class="origin-price">
                                                <span class="original-price">
                                                    <?= number_format(htmlspecialchars($product['original_price']), 3) ?>
                                                </span>
                                                <span class="discount">
                                                    <?php
                                                    // Calculate the discount percentage
                                                    $discount = (($product['original_price'] - $product['price']) / $product['original_price']) * 100;
                                                    echo "-" . number_format($discount, 0) . '%';
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
            </section>

            <!-- Cam -->
            <section>
                <h2 class="section-header">Danh sách sản phẩm Cam</h2>
                <ul class="product">
                    <?php if (!empty($sanPhamCam)): ?>
                        <?php foreach ($sanPhamCam as $product): ?>
                            <li class="product-item">
                                <a href="">
                                    <p class="product-img-container">
                                        <img class="product-img"
                                            src="<?= htmlspecialchars($product['image_url']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                    </p>
                                    <h4>
                                        <?= htmlspecialchars($product['name']) ?>
                                    </h4>
                                    <p>
                                        <?= htmlspecialchars($product['price']) ?>&nbsp;₫
                                    </p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </section>
        </main>
    </article>

    <?php
    include 'footer.php';
    ?>
</body>

</html>