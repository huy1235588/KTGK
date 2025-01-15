<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .aside {
        margin: 20px 0;
        cursor: grab;
    }

    .aside-img {
        width: 100%;
        height: 250px;
    }

    .swiper {
        width: 100%;
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
include 'utils/db_connect.php';
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
                        <img
                            class="aside-img"
                            src="<?= htmlspecialchars($headerImage['headerImage']) ?>/anh_bia.jpg"
                            alt="">
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
            slidesPerView: 3, // Hiển thị 1 slide mỗi lần
            spaceBetween: 0, // Khoảng cách giữa các slide
            speed: 3000,
            freeMode: true, // Tự động cuộn
            freeModeMomentum: false, // Tắt momentum để cuộn liền
        });
    });
</script>