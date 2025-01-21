<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<link rel="stylesheet" href="components/skeleton-loader.css" />
<script src="components/skeleton-loader.js"></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .aside {
        margin: 0 0 20px;
        cursor: grab;
    }

    .aside .swiper {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .aside .skeleton-wrapper {
        --scale-factor: 1.5;
        position: relative;
        width: calc(460px * var(--scale-factor));
        height: calc(215px * var(--scale-factor));
        overflow: hidden;
    }

    .aside .swiper-slide {
        width: auto;
        height: auto;
    }

    /* override swipers transition */
    .swiper>.swiper-wrapper {
        -webkit-transition-timing-function: linear !important;
        -o-transition-timing-function: linear !important;
        transition-timing-function: linear !important;
    }

    .aside-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<?php
// Nếu MoKetNoi() đã tồn tại
if (!function_exists('MoKetNoi')) {
    include 'utils/db_connect.php';
}

// Mở kết nối
$conn = MoKetNoi();

// Truy vấn headerImage
$sqlHeaderImage = "SELECT headerImage
FROM products
WHERE isActive = 1
ORDER BY RAND()
LIMIT 8";

// Thực thi truy vấn
$headerImages = $conn->query($sqlHeaderImage);

// Đóng kết nối
DongKetNoi($conn);
?>

<!-- Slider main container -->
<aside class="aside">
    <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <?php if (!empty($headerImages)): ?>
                <?php foreach ($headerImages as $headerImage): ?>
                    <div class="swiper-slide">
                        <div class="skeleton-wrapper">
                            <!-- Skeleton loader -->
                            <div class="skeleton skeleton-img"></div>

                            <!-- Ảnh -->
                            <img
                                class="aside-img"
                                src=""
                                data-src="<?= htmlspecialchars($headerImage['headerImage']) ?>"
                                alt=""
                                loading="lazy" />
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const swiper = new Swiper('.aside .swiper', {
            // Thêm các tùy chọn sau
            loop: true, // Nối tiếp các slide
            autoplay: {
                delay: 0,
                disableOnInteraction: false, // Không dừng autoplay khi người dùng tương tác
            },
            slidesPerView: 'auto',
            spaceBetween: 0, // Khoảng cách giữa các slide
            speed: 5000,
            freeMode: true,
            freeModeMomentum: false,
        });
    });
</script>