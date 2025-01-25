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
    $conn = MoKetNoi();

    // Lấy thể loại từ URL
    $genre = $_GET['genre'];

    // Truy vấn danh sách sản phẩm theo thể loại
    $sqlProducts = "SELECT *
    FROM products p JOIN product_genres pg ON p.id = pg.product_id
                    JOIN genres g ON pg.genre_id = g.id
    WHERE g.name = '$genre'";

    // Thực thi truy vấn
    $products = $conn->query($sqlProducts);

    // Đóng kết nối
    DongKetNoi($conn);
    ?>

    <!-- Content -->
    <article class="container">
        <!-- Content chính -->
        <main class="main">
            <section>
                <ul class="product">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <li class="product-item">
                                <a href="product.php?id=<?= htmlspecialchars($product['product_id']) ?>">
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
            </section>
        </main>
    </article>

    <?php
    include 'footer.php';
    ?>
</body>

</html>