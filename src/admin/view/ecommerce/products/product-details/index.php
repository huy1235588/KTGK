<?php
$title = 'Product Details';
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Product Details'];

ob_start();
require_once __DIR__ . '../../../../../../controller/ProductController.php';

$productId = $_GET['id'];
$conn = MoKetNoi();

// Khởi tạo ProductController
$productController = new ProductController($conn);

// Lấy thông tin sản phẩm theo ID
$product = $productController->getProductById($productId);

// Lấy thông tin chi tiết sản phẩm theo ID
$tables = [
    'product_screenshots',
    'product_videos',
    'product_developers',
    'product_publishers',
    'product_platforms',
    'product_genres',
    'product_tags',
    'product_features',
    'product_languages',
];
$productDetails = $productController->getProductDetailsById($productId, $tables);
?>

<link rel="stylesheet" href="product-details.css">

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products', 'Product Details'];
        $pageHeader = 'Product Details';
        include '../../../../components/page-header.php';
        ?>

        <!-- Điều hướng sản phẩm tiếp theo hoặc trước đó -->
        <div class="product-navigation">
            <a href="/admin/view/ecommerce/products/product-details/?id=<?php echo $productId - 1 ?>"
                class="product-navigation-link"
                data-tooltip="Previous Product"
                data-placement="top">

                <!-- Icon -->
                <img src="/admin/assets/icon/arrow1-left.svg" alt="Previous Product">
            </a>
            <a href="/admin/view/ecommerce/products/product-details/?id=<?php echo $productId + 1 ?>"
                class="product-navigation-link"
                data-tooltip="Next Product"
                data-placement="top">

                <!-- Icon -->
                <img src="/admin/assets/icon/arrow1-right.svg" alt="Next Product">
            </a>
        </div>
    </div>

    <!-- Product details -->
    <div class="product-details-container">
        <!-- Image -->
        <div class="product-image">
            <img src="<?php echo $product['headerImage'] ?>" alt="Product Image">
        </div>

        <!-- Information -->
        <div class="product-information">
            <!-- Product Table -->
            <div class="product">
                <!-- Title -->
                <h1 class="product-title"><?php echo $product['title'] ?></h1>

                <!-- Type -->
                <div class="product-type">
                    <span class="product-type-label">Type:</span>
                    <span class="product-type-value"><?php echo $product['type'] ?></span>
                </div>

                <!-- Description -->
                <div class="product-description">
                    <span class="product-description-label">Description:</span>
                    <span class="product-description-value"><?php echo $product['description'] ?></span>
                </div>

                <!-- Price -->
                <div class="product-price">
                    <span class="product-price-label">Price:</span>
                    <span class="product-price-value"><?php echo $product['price'] ?></span>
                </div>

                <!-- Discount -->
                <div class="product-discount">
                    <span class="product-discount-label">Discount:</span>
                    <span class="product-discount-value"><?php echo $product['discount'] ?></span>
                </div>

                <!-- Discount Start Date -->
                <div class="product-discount-start-date">
                    <span class="product-discount-start-date-label">Discount Start Date:</span>
                    <span class="product-discount-start-date-value"><?php echo $product['discountStartDate'] ?></span>
                </div>

                <!-- Discount End Date -->
                <div class="product-discount-end-date">
                    <span class="product-discount-end-date-label">Discount End Date:</span>
                    <span class="product-discount-end-date-value"><?php echo $product['discountEndDate'] ?></span>
                </div>

                <!-- Release Date -->
                <div class="product-release-date">
                    <span class="product-release-date-label">Release Date:</span>
                    <span class="product-release-date-value"><?php echo $product['releaseDate'] ?></span>
                </div>

                <!-- Rating -->
                <div class="product-rating">
                    <span class="product-rating-label">Rating:</span>
                    <span class="product-rating-value"><?php echo $product['rating'] ?></span>
                </div>

                <!-- Is active -->
                <div class="product-is-active">
                    <span class="product-is-active-label">Is Active:</span>
                    <span class="product-is-active-value"><?php echo $product['isActive'] ?></span>
                </div>
            </div>

            <!-- Developer Table -->
            <div class="product-developer">
                <span class="product-developer-label">Developer:</span>
                <span>
                    <?php
                    foreach ($productDetails['product_developers'] as $developer) {
                        echo $developer['developer'] . ', ';
                    }
                    ?>
                </span>
            </div>

            <!-- Publisher Table -->
            <div class="product-publisher">
                <span class="product-publisher-label">Publisher:</span>
                <span>
                    <?php
                    foreach ($productDetails['product_publishers'] as $publisher) {
                        echo $publisher['publisher'] . ', ';
                    }
                    ?>
                </span>
            </div>

            <!-- Platforms Table -->
            <div class="product-platform">
                <span class="product-platform-label">Platforms:</span>
                <span>
                    <?php
                    foreach ($productDetails['product_platforms'] as $platform) {
                        echo $platform['platform'] . ', ';
                    }
                    ?>
                </span>
            </div>

            <!-- Genres Table -->
            <div class="product-genre">
                <span class="product-genre-label">Genres:</span>
                <span>
                    <?php
                    foreach ($productDetails['product_genres'] as $genre) {
                        echo $genre['name'] . ', ';
                    }
                    ?>
                </span>
            </div>

            <!-- Tags Table -->
            <div class="product-tag">
                <span class="product-tag-label">Tags:</span>
                <span>
                    <?php
                    foreach ($productDetails['product_tags'] as $tag) {
                        echo $tag['name'] . ', ';
                    }
                    ?>
                </span>
            </div>

            <!-- Features Table -->
            <div class="product-feature">
                <span class="product-feature-label">Features:</span>
                <span>
                    <?php
                    foreach ($productDetails['product_features'] as $feature) {
                        echo $feature['feature'] . ', ';
                    }
                    ?>
                </span>
            </div>

            <!-- Languages Table -->
            <div class="product-language">
                <span>
                    <span class="product-language-label">Languages:</span>
                </span>
                <?php
                $languages = $productDetails['product_languages'];
                for ($i = 0; $i < count($languages); $i++): ?>
                    <div class="product-language-item">
                        <span>
                            <?php echo $languages[$i]['language'] . ': '; ?>
                        </span>

                        <span class="product-language-label">Interface:</span>
                        <span>
                            <?php
                            // 1 => True, 0 => False
                            $interface = $languages[$i]['interface'] == 1 ? 'True' : 'False';
                            echo $interface . ', ';
                            ?>
                        </span>

                        <span class="product-language-label">Full Audio:</span>
                        <span>
                            <?php
                            // 1 => True, 0 => False
                            $fullAudio = $languages[$i]['fullAudio'] == 1 ? 'True' : 'False';
                            echo $fullAudio . ', ';
                            ?>
                        </span>

                        <span class="product-language-label">Subtitles:</span>
                        <span>
                            <?php
                            // 1 => True, 0 => False
                            $subtitles = $languages[$i]['subtitles'] == 1 ? 'True' : 'False';
                            echo $subtitles . ', ';
                            ?>
                        </span>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Screenshots Table -->
            <div class="product-screenshot">
                <span class="product-screenshot-label">Screenshots:</span>
                <div class="product-screenshot-images">
                    <?php
                    foreach ($productDetails['product_screenshots'] as $screenshot) {
                        echo '<img src="' . $screenshot['screenshot'] . '" alt="Screenshot">';
                    }
                    ?>
                </div>
            </div>

            <!-- Videos Table -->
            <div class="product-video">
                <span class="product-video-label">Videos:</span>
                <div class="product-video-videos">
                    <?php
                    foreach ($productDetails['product_videos'] as $video) {
                        echo '<video src="' . $video['mp4'] . '" controls></video>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/components/tooltip.js"></script>
</article>


<?php
$content = ob_get_clean();
include '../../../../layout.php';

DongKetNoi($conn);
