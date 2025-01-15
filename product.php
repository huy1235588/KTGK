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
    $name = htmlspecialchars($product['title']);
    $price = floatval(htmlspecialchars($product['price']));
    $discount = intval(htmlspecialchars($product['discount']));
    $description = htmlspecialchars($product['description']);
} else {
    die("Sản phẩm không tồn tại.");
}

// Đóng kết nối
$stmt->close();
DongKetNoi($conn);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name ?></title>
    <link rel="stylesheet" href="css/product.css">
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

    <?php
    $conn = MoKetNoi();

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

    // Truy vấn danh sách videos
    $stmt = $conn->prepare("SELECT * FROM product_videos WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $videos = [];
    // Lấy danh sách videos
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $videos[] = array(
                'mp4' => $row['mp4'],
                'webm' => $row['webm'],
                'thumbnail' => $row['thumbnail']
            );
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

    // Truy vấn features
    $stmt = $conn->prepare("SELECT * FROM product_features WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách features
    $features = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $features[] = $row;
        }
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
            <!-- Tiêu đề -->
            <h1 class="product-name">
                <?= $name ?>
            </h1>

            <!-- Media -->
            <section class="media-container">
                <div class="swiper product-media">
                    <ul class="swiper-wrapper">
                        <!-- Videos -->
                        <?php foreach ($videos as $video): ?>
                            <li class="swiper-slide">
                                <?php
                                // Nếu nguồn video là youtube
                                if (strpos($video['mp4'], 'youtube') !== false):
                                    // Lấy ID video
                                    $videoId = explode('=', $video['mp4'])[1];

                                    // Nếu video đầu tiên, tự động phát
                                    if ($video === $videos[0]) {
                                        $autoplay = 1;
                                    } else {
                                        $autoplay = 0;
                                    }
                                ?>
                                    <iframe
                                        class="youtube-video"
                                        src="https://www.youtube.com/embed/<?= $videoId ?>?enablejsapi=1&mute=1&autoplay=<?= $autoplay ?>"
                                        data-video-id="<?= $videoId ?>"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>;
                                <?php else: ?>
                                    <video
                                        controls
                                        preload="metadata"
                                        poster="<?= $video['thumbnail'] ?>"
                                        muted
                                        autoplay>
                                        <source src="<?= $video['mp4'] ?>" type="video/mp4">
                                        <source src="<?= $video['webm'] ?>" type="video/webm">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                        <!-- Image -->
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
                        <?php foreach ($videos as $video): ?>
                            <li class="swiper-slide">
                                <img src="<?= $video['thumbnail'] ?>" alt="<?= $name ?>" class="thumb-img">
                                <div class="play-icon"></div>
                            </li>
                        <?php endforeach; ?>

                        <?php foreach ($screenshot as $img): ?>
                            <li class="swiper-slide">
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($name) ?>" class="thumb-img">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Thanh trượt -->
                    <div class="swiper-scrollbar"></div>
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
            <!-- Hình ảnh header -->
            <p class="product-header-img-container">
                <img class="product-header-img"
                    src="<?= htmlspecialchars($product['headerImage']) ?>"
                    alt="<?= htmlspecialchars($product['title']) ?>">
            </p>

            <div class="product-info">
                <!-- Price -->
                <div class="price-container">
                    <span class="price">
                        <?php
                        $priceFormat = number_format($product['price'], 2);
                        if ($priceFormat == 0) {
                            echo "Free to play";
                        } else {
                            echo "$" . $priceFormat;
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

                                <?= "$" . number_format($originPrice, 2) ?>
                            </span>
                            <span class="discount">
                                <?php
                                echo "-" .  number_format(htmlspecialchars($product['discount']), 0) . "%";
                                ?>
                            </span>
                        </p>
                    <?php endif ?>
                </div>

                <!-- Nút mua -->
                <button class="buy-btn">
                    BUY NOW
                </button>

                <!-- Nút giỏ hàng -->
                <button class="cart-btn">
                    ADD TO CART
                </button>

                <!-- Features -->
                <div class="features">
                    <h3 class="title">
                        Features
                    </h3>
                    <ul class="feature-list">
                        <?php foreach ($features as $feature): ?>
                            <li class="feature-item">
                                <a href="#" class="feature-link">
                                    <span class="feature-icon">
                                        <img
                                            <?php
                                            // Chuyển chuỗi feature về chữ thường để so sánh không phân biệt hoa thường
                                            $featureLower = trim(strtolower($feature['feature']));

                                            // Nếu chuỗi feature có remote ở đầu -> ico_remote_play.png
                                            if (stripos($feature['feature'], 'Remote') === 0) {
                                                $featureIcon = 'ico_remote_play.png';
                                            }
                                            // Nếu có pvp hoặc MMO hoặc multiplayer-> ico_multiplayer.png
                                            else if (stripos($featureLower, 'pvp') !== false || stripos($featureLower, 'mmo') !== false || stripos($featureLower, 'multiplayer') !== false) {
                                                $featureIcon = 'ico_multiplayer.png';
                                            }

                                            // Nếu có Co-op -> ico_coop.png
                                            else if (stripos($featureLower, 'co-op') !== false) {
                                                $featureIcon = 'ico_coop.png';
                                            }

                                            // Nếu có sdk -> ico_sdk.png
                                            else if (stripos($featureLower, 'sdk') !== false) {
                                                $featureIcon = 'ico_sdk.png';
                                            }

                                            // Nếu có editor -> ico_editor.png
                                            else if (stripos($featureLower, 'editor') !== false) {
                                                $featureIcon = 'ico_editor.png';
                                            }

                                            // Nếu có chữ "available" -> ico_ + tên feature + .png
                                            else if (stripos($featureLower, 'available') !== false) {
                                                $featureIcon = 'ico_' . trim(str_replace('available', '', $featureLower)) . '.png';
                                            }

                                            // Nếu có chữ "steam" -> ico_ + tên feature + .png
                                            else if (stripos($featureLower, 'steam') !== false) {
                                                $featureIcon = 'ico_' . trim(str_replace('steam', '', $featureLower)) . '.png';
                                                // Chuyển khoảng cách thành dấu _
                                                $featureIcon = str_replace(' ', '_', $featureIcon);
                                            }

                                            // Mặc định chuyển chuỗi feature thành tên file icon
                                            else {
                                                $featureIcon = 'ico_' . str_replace(' ', '_', $featureLower) . '.png';
                                            }
                                            ?>


                                            src="assets/icons/features/<?= $featureIcon ?>"
                                            alt="<?= htmlspecialchars($feature['feature']) ?>">
                                    </span>
                                    <span class="feature-title">
                                        <?= htmlspecialchars($feature['feature']) ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>


            </div>
        </aside>
    </article>

    <?php
    include 'footer.php';
    ?>

    <script src="js/product.js"></script>
</body>

</html>