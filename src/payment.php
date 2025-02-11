<?php
include 'utils/db_connect.php';
$conn = MoKetNoi();

session_start();

$sql = "SELECT * 
FROM cart
WHERE user_id = {$_SESSION['user']['id']}";
$result = mysqli_query($conn, $sql);
$carts = mysqli_fetch_all($result, MYSQLI_ASSOC);

$products = [];
if (!empty($carts)) {
    // Lấy danh sách sản phẩm từ giỏ hàng
    $productIds = array_column($carts, 'product_id');

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
            p2.name AS platform
        FROM products p
        LEFT JOIN product_platforms pp ON p.id = pp.product_id
        LEFT JOIN platforms p2 ON pp.platform_id = p2.id
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
foreach ($carts as $item) {
    $productId = $item['product_id'];
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

// Lưu thông tin giỏ hàng vào session
$_SESSION['cart'] = $cart;
$_SESSION['total'] = $total;

// Đóng kết nối
DongKetNoi($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="css/payment.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
</head>

<body>
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <!-- main -->
    <main class="container">
        <h1 class="header-title">
            Checkout
        </h1>
        <?php if (empty($cart)) : ?>
            <div class="empty-cart">
                <h1 class="header-title">
                    Your shopping cart
                </h1>
                <span class="empty-cart-icon" aria-hidden="true" data-testid="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 52">
                        <g fill="none" fill-rule="evenodd">
                            <path d="M4.058 0C1.094 0 0 1.098 0 4.075v35.922c0 .338.013.65.043.94.068.65-.043 1.934 2.285 2.96 1.553.683 7.62 3.208 18.203 7.573 1.024.428 1.313.529 2.081.529.685.013 1.137-.099 2.072-.53 10.59-4.227 16.66-6.752 18.213-7.573 2.327-1.23 2.097-3.561 2.097-3.899V4.075C44.994 1.098 44.13 0 41.166 0H4.058z" fill="#404044"></path>
                            <path stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M14 18l4.91 2.545-2.455 4M25.544 28.705c-1.056-.131-1.806-.14-2.25-.025-.444.115-1.209.514-2.294 1.197M29.09 21.727L25 19.5l2.045-3.5"></path>
                        </g>
                    </svg>
                </span>
                <h2 class="empty-cart-title">
                    Your cart is empty.
                </h2>
                <a type="button"
                    role="link"
                    class="empty-cart-link"
                    href="index.php">
                    <span class="">
                        Shop for Games &amp; Apps
                    </span>
                </a>
            </div>
        <?php else: ?>
            <div class="checkout-container">
                <!-- Payment Method -->
                <div class="payment-section">
                    <h2>Payment Method</h2>

                    <div class="payment-methods">
                        <div class="method-card active" data-method="credit">
                            <input type="radio" name="paymentMethod" id="credit" required>
                            <label for="credit">Credit/Debit Card</label>
                        </div>
                        <div class="method-card" data-method="momo">
                            <input type="radio" name="paymentMethod" id="momo">
                            <label for="momo">MoMo Wallet</label>
                        </div>
                        <div class="method-card" data-method="zalopay">
                            <input type="radio" name="paymentMethod" id="zalopay">
                            <label for="zalopay">ZaloPay</label>
                        </div>
                        <div class="method-card" data-method="paypal">
                            <input type="radio" name="paymentMethod" id="paypal">
                            <label for="paypal">PayPal</label>
                        </div>
                    </div>

                    <form id="paymentForm" action="api/payment.php" method="POST">
                        <input type="hidden" name="paymentMethodForm" id="paymentMethod" value="credit">

                        <!-- Credit Card Fields -->
                        <div class="payment-details" id="creditDetails">
                            <div class="form-group">
                                <label for="cardNumber">Card Number</label>
                                <input type="text" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="form-group">
                                <label for="expiryDate">Expiry Date</label>
                                <input type="month" id="expiryDate" name="expiry_date">
                            </div>
                            <div class="form-group">
                                <label for="cvc">CVC</label>
                                <input type="text" id="cvc" name="cvc" placeholder="123">
                            </div>
                        </div>

                        <!-- ZALO PAY -->
                        <div class="payment-details" id="zalopayDetails">
                            <div class="form-group">
                                <label for="zaloNumber">Phone Number</label>
                                <input type="text" id="zaloNumber" name="zalo_number" placeholder="0987654321">
                            </div>
                        </div>

                        <!-- MOMO -->
                        <div class="payment-details" id="momoDetails">
                            <div class="form-group momo">
                                <label for="momoNumber">Phone Number</label>
                                <input type="text" id="momoNumber" name="momo_number" placeholder="0987654321">
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="payment-details" id="paypalDetails">
                            <div class="form-group paypal">
                                <label for="paypalEmail">Email</label>
                                <input type="email" id="paypalEmail" name="paypal_email" placeholder="email">
                            </div>
                        </div>

                        <button type="submit" class="pay-button">Complete Payment</button>
                    </form>
                </div>
                <!-- Order Summary -->
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
                        <li class="sumray-continue">
                            <a href="index.php">
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
                            <p class="card-payment-icons">
                                <img src="assets/icons/payment/visa.svg" alt="">
                                <img src="assets/icons/payment/mastercard.svg" alt="">
                                <img src="assets/icons/payment/paypal.png" alt="">
                                <img src="assets/icons/payment/zalo-pay.png" alt="">
                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png" alt="">
                            </p>
                        </li>
                    </ul>
                </aside>
            </div>
        <?php endif; ?>
    </main>

    <script src="js/payment.js"></script>

    <?php include 'footer.php'; ?>
</body>

</html>