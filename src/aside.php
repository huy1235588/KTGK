<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<link rel="stylesheet" href="components/skeleton-loader.css" />
<script src="components/skeleton-loader.js"></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .aside {
        margin: 20px 0;
        cursor: grab;
    }

    .aside .swiper {
        width: 100%;
        height: 215px;
        overflow: hidden;
    }

    .aside .skeleton-wrapper {
        position: relative;
        width: 100%;
        height: 215px;
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
                delay: 0, // Thời gian chuyển đổi giữa các slide (3 giây)
                disableOnInteraction: false, // Không dừng autoplay khi người dùng tương tác
            },
            slidesPerView: 'auto', // Hiển thị 1 slide mỗi lần
            spaceBetween: 0, // Khoảng cách giữa các slide
            speed: 3000,
            freeMode: true, // Tự động cuộn
            freeModeMomentum: false, // Tắt momentum để cuộn liền
        });
    });
</script>