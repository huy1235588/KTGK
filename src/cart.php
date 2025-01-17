<?php
include 'utils/db_connect.php';
$conn = MoKetNoi();

session_start();

// Lấy giỏ hàng từ session
$sessionCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$products = [];
if (!empty($sessionCart)) {
    // Lấy danh sách sản phẩm từ giỏ hàng
    $productIds = array_column($sessionCart, 'productId');

    // Chuyển mảng id sang chuỗi để truy vấn
    $productIdsString = implode(',', $productIds);

    // Truy vấn lấy thông tin sản phẩm và platform
    $sql = "
        SELECT 
            p.id AS productId,
            p.title,
            p.price,
            p.discount,
            p.headerImage,
            pp.platform
        FROM products p
        LEFT JOIN product_platforms pp ON p.id = pp.product_id
        WHERE p.id IN ($productIdsString)
    ";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Chuyển kết quả từ query sang mảng
        while ($row = mysqli_fetch_assoc($result)) {
            $productId = $row['productId'];

            // Nếu sản phẩm chưa tồn tại trong mảng $products, khởi tạo
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'productId' => $productId,
                    'title' => $row['title'],
                    'price' => $row['price'],
                    'discount' => $row['discount'],
                    'headerImage' => $row['headerImage'],
                    'platforms' => [] // Mảng lưu danh sách platform
                ];
            }

            // Thêm platform vào danh sách platforms
            if (!empty($row['platform'])) {
                $products[$productId]['platforms'][] = $row['platform'];
            }
        }
    }
}

// Tạo giỏ hàng với thông tin sản phẩm đầy đủ
$cart = [];
foreach ($sessionCart as $item) {
    $productId = $item['productId'];
    if (isset($products[$productId])) {
        // Lấy thông tin sản phẩm
        $product = $products[$productId];

        // Tính giá gốc trước khi giảm giá
        $basePrice = $product['price'] / (1 -  $product['discount'] / 100);

        $cart[] = [
            'productId' => $productId,
            'title' => $product['title'],
            'basePrice' => $basePrice,
            'price' => $product['price'],
            'discount' => $product['discount'],
            'headerImage' => $product['headerImage'],
            'platforms' => $product['platforms'], // Thêm danh sách platform
        ];
    }
}

// Tính tổng giá trị giỏ hàng
$total = array_reduce($cart, function ($sum, $item) {
    return $sum + $item['price'];
}, 0);

// Tính tổng giá gốc trước khi giảm giá
$baseTotal = array_reduce($cart, function ($sum, $item) {
    return $sum + $item['basePrice'];
}, 0);

// Tính % giảm giá
$discount = $baseTotal - $total;

// Đóng kết nối
DongKetNoi($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="css/cart.css">
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

    <!-- main -->
    <main class="container">
        <article class="product-cart">
            <h2 class="header-title">
                Your shopping cart
            </h2>

            <?php if (empty($cart)) : ?>
                <p class="empty-cart">
                    Your shopping cart is empty
                </p>
            <?php else: ?>
                <ul class="product-cart-list">
                    <?php foreach ($cart as $item) : ?>
                        <li class="product-cart-item">
                            <!-- Hình ảnh -->
                            <a class="product-cart-img" href="product.php?id=<?= $item['productId'] ?>">
                                <img src="<?= $item['headerImage'] ?>" alt="">
                            </a>

                            <!-- Thông tin sản phẩm -->
                            <section class="product-cart-info">
                                <a href="product.php?id=<?= $item['productId'] ?>" class="product-cart-name">
                                    <?= $item['title'] ?>
                                </a>
                                <p class="product-cart-platform">
                                    <?php foreach ($item['platforms'] as $platform) : ?>
                                        <img src="assets/icons/platform/<?= $platform ?>.svg" alt="">
                                    <?php endforeach; ?>
                                </p>
                                <ul class="product-cart-detail">
                                    <li class="product-cart-action">
                                        <button class="button-remove" type="button" name="button-remove" data-id="<?= $item['productId'] ?>">
                                            <span class="button-remove-text">
                                                Remove
                                            </span>
                                        </button>
                                    </li>
                                    <li class="product-cart-price">
                                        <?php if ($item['discount'] <= 0) : ?>
                                            <span class="price-final">
                                                $<?= number_format($item['price'], 2) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="discount">
                                                -<?= number_format($item['discount'], 0) ?>%
                                            </span>

                                            <p class="price-info">
                                                <span class="price-base
                                            ">
                                                    $<?= number_format($item['basePrice'], 2) ?>
                                                </span>
                                                <span class="price-final">
                                                    $<?= number_format($item['price'], 2) ?>
                                                </span>
                                            </p>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </section>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
        <aside class="summary">
            <h2 class="summary-title">
                Summary
            </h2>
            <ul class="sumary-list">
                <li class="sumary-item">
                    <span class="sumary-label">
                        Price
                    </span>
                    <span class="sumary-value">
                        $<?= number_format($basePrice, 2) ?>
                    </span>
                </li>
                <li class="sumary-item">
                    <span class="sumary-label">
                        Sale Discount
                    </span>
                    <span class="sumary-value">
                        -$<?= number_format($discount, 2) ?>
                    </span>
                </li>
                <li class="sumary-item sumary-total">
                    <span class="sumary-label">
                        Total
                        <span class="sumary-label-taxes">
                            Incl. taxes
                        </span>
                    </span>
                    <span class="sumary-value">
                        $<?= number_format($total, 2) ?>
                    </span>
                </li>
                <li class="sumray-button">
                    <button>
                        CHECK OUT
                    </button>
                </li>
                <li class="sumray-continue">
                    <a href="trangchu.php">
                        Continue shopping
                    </a>
                </li>
                <li>
                    <p class="summary-secured">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" width="20px"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.0049 2L18.3032 4.28071C18.7206 4.41117 19.0049 4.79781 19.0049 5.23519V7H21.0049C21.5572 7 22.0049 7.44772 22.0049 8V10H9.00488V8C9.00488 7.44772 9.4526 7 10.0049 7H17.0049V5.97L11.0049 4.094L5.00488 5.97V13.3744C5.00488 14.6193 5.58406 15.7884 6.56329 16.5428L6.75154 16.6793L11.0049 19.579L14.7869 17H10.0049C9.4526 17 9.00488 16.5523 9.00488 16V12H22.0049V16C22.0049 16.5523 21.5572 17 21.0049 17L17.7848 17.0011C17.3982 17.5108 16.9276 17.9618 16.3849 18.3318L11.0049 22L5.62486 18.3318C3.98563 17.2141 3.00488 15.3584 3.00488 13.3744V5.23519C3.00488 4.79781 3.28913 4.41117 3.70661 4.28071L11.0049 2Z">
                            </path>
                        </svg>
                        <span>
                            Secured transaction
                        </span>
                    </p>
                </li>
                <li>
                    <p class="card-payment">
                        <img src="assets/icons/payment/visa.svg" alt="">
                        <img src="assets/icons/payment/mastercard.svg" alt="">
                    </p>
                </li>
            </ul>
        </aside>
    </main>

    <script>
        // Xoá sản phẩm khỏi giỏ hàng
        const buttonsRemove = document.querySelectorAll('.button-remove');

        buttonsRemove.forEach(button => {
            button.addEventListener('click', async () => {
                const productId = button.dataset.id;

                // Gửi request lên server để xoá sản phẩm khỏi giỏ hàng
                const response = await fetch(`api/cart.php?productId=${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                });

                if (response.ok) {
                    // Xoá sản phẩm khỏi giao diện
                    const productItem = button.closest('.product-cart-item');
                    productItem.remove();

                    // Hiển thị thông báo
                    setNotification('Product removed from cart successfully.', 'success');
                }
            });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>