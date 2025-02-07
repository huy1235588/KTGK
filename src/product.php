<?php
include 'utils/db_connect.php';

$conn = MoKetNoi();

// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra sản phẩm có isAcitve = 1 không
$stmt = $conn->prepare("SELECT isActive FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

// Nếu sản phẩm không active thì chuyển hướng về trang chủ
$isActive = $result->fetch_assoc()['isActive'];
if ($isActive == 0) {
    header('Location: index.php');
    exit();
}

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

    // 2024-08-20 00:00:00 => 20 August, 2024
    $releaseDate = date('d F, Y', strtotime($product['releaseDate']));
    $discountEndDate = date('d F, Y', strtotime($product['discountEndDate']));
    $detail = $product['detail'];
} else {
    die("Sản phẩm không tồn tại.");
}

// Đóng kết nối
$stmt->close();
DongKetNoi($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name ?></title>
    <link rel="stylesheet" href="css/product.css">
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

    // Lấy danh sách developer
    $stmt = $conn->prepare("SELECT developer FROM product_developers WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $developers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $developers[] = $row['developer'];
        }
    }

    // Lấy danh sách publisher
    $stmt = $conn->prepare("SELECT publisher FROM product_publishers WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $publishers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $publishers[] = $row['publisher'];
        }
    }

    // Lấy genre của sản phẩm
    $sql = "SELECT name AS genre, genres.id as id
    FROM product_genres JOIN genres ON product_genres.genre_id = genres.id
    WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $genre = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genre[] = $row;
        }
    }

    // Truy vấn tags
    $sql = "SELECT name AS tag, tags.id as id
    FROM product_tags JOIN tags ON product_tags.tag_id = tags.id
    WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách tags
    $tags = [];
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row;
    }

    // Truy vấn features
    $stmt = $conn->prepare("SELECT * 
    FROM product_features JOIN features ON product_features.feature_id = features.id
    WHERE product_id = ?");
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

    // Lấy danh sách system requirements
    $stmt = $conn->prepare("SELECT * FROM product_system_requirements WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách system requirements
    $windows = [];
    $mac = [];
    $linux = [];
    $systemRequirements = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['platform'] == 'win') {
                $windows[] = $row;
            } else if ($row['platform'] == 'mac') {
                $mac[] = $row;
            } else if ($row['platform'] == 'linux') {
                $linux[] = $row;
            }
        }
    }

    // Lấy danh sách languages
    $stmt = $conn->prepare("SELECT * 
    FROM product_languages JOIN languages ON product_languages.language_id = languages.id
    WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách languages
    $languages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $languages[] = $row['name'];
            $interfaceLanguages[] = $row['interface'];
            $fullAudioLanguages[] = $row['fullAudio'];
            $subtitlesLanguages[] = $row['subtitles'];
        }
    }

    // Lấy danh sách achievements
    $stmt = $conn->prepare("SELECT title, image 
    FROM product_achievements 
    WHERE product_id = ? 
    LIMIT 3");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách achievements
    $achievements = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $achievements[] = $row;
        }
    }

    // Lấy tổng số lượng achievements
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM product_achievements WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalAchievements = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm cùng genres
    $stmt = $conn->prepare("SELECT DISTINCT p.*
        FROM products p
        JOIN product_genres pg ON p.id = pg.product_id
        JOIN genres g ON pg.genre_id = g.id
        WHERE g.name IN (
            SELECT name AS genre
            FROM product_genres JOIN genres ON product_genres.genre_id = genres.id
            WHERE product_id = ?
        )
        AND p.id != ?
        ORDER BY RAND()
        LIMIT 8
    ");
    $stmt->bind_param("ii", $productId, $productId); // Truyền hai giá trị $productId
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách sản phẩm cùng genres
    $relatedProducts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $relatedProducts[] = $row; // Thêm sản phẩm vào danh sách
        }
    }

    // Đóng kết nối
    $stmt->close();
    DongKetNoi($conn);
    ?>

    <script>
        const sessionCart = <?php echo json_encode($_SESSION['cart']); ?> || [];
    </script>

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
                                <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy">
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
                                <img
                                    class="thumb-img"
                                    src="<?= $video['thumbnail'] ?>"
                                    alt="<?= $name ?>"
                                    loading="lazy">
                                <div class="play-icon"></div>
                            </li>
                        <?php endforeach; ?>

                        <?php foreach ($screenshot as $img): ?>
                            <li class="swiper-slide">
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($name) ?>" class="thumb-img" loading="lazy">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Thanh trượt -->
                    <div class="swiper-scrollbar"></div>
                </div>
            </section>

            <!-- Thông tin chi tiết -->
            <section class="section product-info">
                <!-- Description -->
                <p class="description">
                    <?= $description ?>
                </p>

                <!-- Tag -->
                <div class="tags">
                    <h2 class="title">
                        Tag:
                    </h2>

                    <div class="tag-container">
                        <?php for ($i = 0; $i < min(count($tags), 6); $i++) : ?>
                            <a href="tags.php?tag=<?= htmlspecialchars($tags[$i]['id']) ?>" class="tag">
                                <?= htmlspecialchars($tags[$i]['tag']) ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Detail -->
                <div class="game_page_autocollapse_ctn collapsed">
                    <!-- Description -->
                    <div class="game_page_autocollapse">
                        <div class="game_area_description" id="game_area_description">
                            <?= $detail ?>
                        </div>
                    </div>

                    <!-- Read more -->
                    <div class="game_page_autocollapse_fade">
                        <div class="game_page_autocollapse_readmore">
                            READ MORE
                        </div>
                    </div>
                </div>

                <!-- System requirements -->
                <div class="system-requirements">
                    <h2 class="title">
                        System Requirements
                    </h2>

                    <!-- Tabs -->
                    <?php if ($mac != null) : ?>
                        <div class="sysreq_tabs">
                            <button class="sysreq_tab active" data-os="win">
                                Windows
                            </button>
                            <button class="sysreq_tab" data-os="mac">
                                Mac OS X
                            </button>
                            <?php if ($linux) : ?>
                                <button class="sysreq_tab" data-os="linux">
                                    SteamOS + Linux
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Window -->
                    <div class="sysreq_contents active" data-os="win">
                        <div class="game_area_sys_req sysreq_content active">
                            <ul class="game_area_sys_req_leftCol">
                                <li>
                                    <strong class="sysreq_label">
                                        MINIMUM:
                                    </strong>
                                </li>
                                <?php foreach ($windows as $win): ?>
                                    <?php if ($win['minimum'] != ""): ?>
                                        <li>
                                            <strong><?= $win['title'] ?></strong>
                                            <span><?= $win['minimum'] ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                            <ul class="game_area_sys_req_rightCol">
                                <li>
                                    <strong class="sysreq_label">
                                        RECOMMENDED:
                                    </strong>
                                </li>
                                <?php foreach ($windows as $win): ?>
                                    <?php if ($win['recommended'] != ""): ?>
                                        <li>
                                            <strong><?= $win['title'] ?></strong>
                                            <span><?= $win['recommended'] ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <?php if ($mac): ?>
                        <!-- MacOS -->
                        <div class="sysreq_contents" data-os="mac">
                            <div class="game_area_sys_req sysreq_content">
                                <ul class="game_area_sys_req_leftCol">
                                    <li>
                                        <strong class="sysreq_label">
                                            MINIMUM:
                                        </strong>
                                    </li>
                                    <?php foreach ($mac as $macOS): ?>
                                        <?php if ($macOS['minimum'] != ""): ?>
                                            <li>
                                                <strong><?= $macOS['title'] ?></strong>
                                                <span><?= $macOS['minimum'] ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <ul class="game_area_sys_req_rightCol">
                                    <li>
                                        <strong class="sysreq_label">
                                            RECOMMENDED:
                                        </strong>
                                    </li>
                                    <?php foreach ($mac as $macOS): ?>
                                        <?php if ($macOS['recommended'] != ""): ?>
                                            <li>
                                                <strong><?= $macOS['title'] ?></strong>
                                                <span><?= $macOS['recommended'] ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($linux): ?>
                        <!-- Linux -->
                        <div class="sysreq_contents" data-os="linux">
                            <div class="game_area_sys_req sysreq_content">
                                <ul class="game_area_sys_req_leftCol">
                                    <li>
                                        <strong class="sysreq_label">
                                            MINIMUM:
                                        </strong>
                                    </li>
                                    <?php foreach ($linux as $linuxOS): ?>
                                        <?php if ($linuxOS['minimum'] != ""): ?>
                                            <li>
                                                <strong><?= $linuxOS['title'] ?></strong>
                                                <span><?= $linuxOS['minimum'] ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <ul class="game_area_sys_req_rightCol">
                                    <li>
                                        <strong class="sysreq_label">
                                            RECOMMENDED:
                                        </strong>
                                    </li>
                                    <?php foreach ($linux as $linuxOS): ?>
                                        <?php if ($linuxOS['recommended'] != ""): ?>
                                            <li>
                                                <strong><?= $linuxOS['title'] ?></strong>
                                                <span><?= $linuxOS['recommended'] ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <!-- Right content -->
        <aside class="right-content">
            <!-- Hình ảnh header -->
            <p class="product-header-img-container">
                <img class="product-header-img"
                    src="<?= htmlspecialchars($product['headerImage']) ?>"
                    alt="<?= htmlspecialchars($product['title']) ?>"
                    loading="lazy">
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

                            <!-- Discount end -->
                        <p class="discount-end">
                            <?= "Offer ends " . $discountEndDate ?>
                        </p>
                        </p>
                    <?php endif ?>
                </div>

                <!-- Nút mua -->
                <button class="buy-btn">
                    BUY NOW
                </button>

                <!-- Nút giỏ hàng -->
                <button type="submit" class="cart-btn" data-id="<?= $productId ?>">
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
                                            $featureLower = trim(strtolower($feature['name']));

                                            // Nếu chuỗi feature có remote ở đầu -> ico_remote_play.png
                                            if (stripos($feature['name'], 'Remote') === 0) {
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
                                            alt="<?= htmlspecialchars($feature['name']) ?>"
                                            loading="lazy">
                                    </span>
                                    <span class="feature-title">
                                        <?= htmlspecialchars($feature['name']) ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Product detail -->
                <div id="genresAndManufacturer" class="details_block">
                    <div class="dev_row">
                        <b>Title:</b>
                        <span>
                            <?= $name ?>
                        </span>
                    </div>

                    <b>Genre:</b>
                    <span data-panel="{&quot;flow-children&quot;:&quot;row&quot;}">
                        <?php foreach ($genre as $g): ?>
                            <a href="/genre.php?id=<?= $g['id'] ?>" class="genre"><?= $g['genre'] ?></a><?php if ($g !== end($genre)) echo ", "; ?>
                        <?php endforeach; ?>
                    </span><br>

                    <div class="dev_row">
                        <b>Developer:</b>

                        <?php foreach ($developers as $dev): ?>
                            <a href="https://store.steampowered.com/search/?developer=<?= $dev ?>&amp;snr=1_5_9__422" class="dev"><?= $dev ?></a><?php if ($dev !== end($developers)) echo ","; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="dev_row">
                        <b>Publisher:</b>

                        <?php foreach ($publishers as $pub): ?>
                            <a href="https://store.steampowered.com/search/?publisher=<?= $pub ?>&amp;snr=1_5_9__422" class="dev"> <?= $pub ?></a><?php if ($pub !== end($publishers)) echo ","; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="dev_row">
                        <b>Release Date:</b>
                        <span>
                            <?= $releaseDate ?>
                        </span>
                    </div>
                </div>

                <!-- Language -->
                <div class="details_block">
                    <h3 class="title">
                        Languages:
                    </h3>
                    <table class="languages-table">
                        <tr>
                            <th></th>
                            <th>Interface</th>
                            <th>Full Audio</th>
                            <th>Subtitles</th>
                        </tr>
                        <?php for ($i = 0; $i < count($languages); $i++): ?>
                            <tr style="<?php if ($i >= 5) echo 'display: none;' ?>">
                                <td class="ellipsis">
                                    <?= $languages[$i] ?>
                                </td>
                                <td class="check-col">
                                    <?php if ($interfaceLanguages[$i] == 1): ?>
                                        ✔
                                    <?php endif; ?>
                                </td>
                                <td class="check-col">
                                    <?php if ($fullAudioLanguages[$i] == 1): ?>
                                        ✔
                                    <?php endif; ?>
                                </td>
                                <td class="check-col">
                                    <?php if ($subtitlesLanguages[$i] == 1): ?>
                                        ✔
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </table>

                    <?php if (count($languages) > 5): ?>
                        <script>
                            function showMoreLanguages(button) {
                                const languagesTable = document.querySelector('.languages-table');
                                const rows = languagesTable.querySelectorAll('tr');

                                rows.forEach((row, index) => {
                                    if (index >= 5) {
                                        row.style.display = 'table-row';
                                    }
                                });

                                button.style.display = 'none';
                            }
                        </script>

                        <!-- Nút xem thêm -->
                        <button class="all_languages" onclick="showMoreLanguages(this)">
                            See all <?= count($languages) ?> supported languages
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Achievements -->
                <?php if ($totalAchievements > 0): ?>
                    <div class="details_block">
                        <div class="dev_row">
                            <b>Achievements:</b>
                            <span>
                                <?= $totalAchievements ?> <?= $totalAchievements > 1 ? 'achievements' : 'achievement' ?>
                            </span>
                        </div>

                        <div class="achievements">
                            <?php foreach ($achievements as $achievement): ?>
                                <img class="achievement"
                                    src="https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/<?= $achievement['image'] ?>"
                                    alt="<?= $achievement['title'] ?>"
                                    title="<?= $achievement['title'] ?>"
                                    loading="lazy">
                            <?php endforeach; ?>
                            <a href="achievement.php?id=<?= $productId ?>"
                                class="view-all-achievements">
                                View
                                <br>
                                all <?= $totalAchievements ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </article>

    <!-- Sản phẩm liên quan -->
    <section class="section product-relate-container">
        <h2 class="title">
            More like this
        </h2>

        <ul class="products">
            <?php foreach ($relatedProducts as $sp): ?>
                <li class="product-item">
                    <a href="product.php?id=<?= htmlspecialchars($sp['id']) ?>">
                        <!-- Hình ảnh sản phẩm -->
                        <p class="product-img-container">
                            <img class="product-img"
                                src="<?= htmlspecialchars($sp['headerImage']) ?>"
                                alt="<?= htmlspecialchars($sp['title']) ?>"
                                loading="lazy">
                        </p>

                        <div class="product-info">
                            <!-- Tên sản phẩm -->
                            <p class="product-name">
                                <?= htmlspecialchars(($sp['title'])) ?>
                            </p>

                            <!-- Price -->
                            <div class="price-container">
                                <div class="price">
                                    <span class="price-text">
                                        <?php
                                        if ($sp['price'] == 0) {
                                            echo "Free";
                                        } else {
                                            echo "$" . number_format(htmlspecialchars($sp['price']), 2);
                                        }
                                        ?>
                                    </span>
                                    <!-- Origin price -->
                                    <span class="original-price">
                                        <?php
                                        // Tình giá gốc
                                        $originPrice = $sp['price'] / (1 - $sp['discount'] / 100);
                                        if ($sp['price'] && htmlspecialchars($sp['discount']) > 0):
                                        ?>
                                            $<?= number_format($originPrice, 2) ?>
                                        <?php endif ?>
                                    </span>
                                </div>
                                <!-- Discount -->
                                <?php if ($sp['price'] && htmlspecialchars($sp['discount']) > 0): ?>
                                    <span class="discount">
                                        <?php
                                        echo "-" .  number_format(htmlspecialchars($sp['discount']), 0) . "%";
                                        ?>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php
    include 'footer.php';
    ?>

    <script src="js/product.js"></script>
</body>

</html>