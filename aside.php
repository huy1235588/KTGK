<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .aside {
        margin: 20px 0;
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
    .swiper-container-free-mode>.swiper-wrapper {
        -webkit-transition-timing-function: linear;
        -o-transition-timing-function: linear;
        transition-timing-function: linear;
        margin: 0 auto;
    }
</style>

<!-- Slider main container -->
<aside class="aside">
    <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
                <img
                    class="aside-img"
                    src="https://www.vinmec.com/static/uploads/large_20200305_150343_235114_hoaquan_max_1800x1800_jpg_915b956f6d.jpg"
                    alt="">
            </div>
            <div class="swiper-slide">
                <img
                    class="aside-img"
                    src="https://www.vinmec.com/static/uploads/small_20191029_092447_845809_7_thuc_pham_giau_vi_max_1800x1800_jpg_d7f5f911e5.jpg"
                    alt="">
            </div>
            <div class="swiper-slide">
                <img
                    class="aside-img"
                    src="https://www.vinmec.com/static/uploads/small_20200729_051027_700925_Lycopene_6_max_1800x1800_jpg_a8993b144c.jpg"
                    alt="">
            </div>
            <div class="swiper-slide">
                <img
                    class="aside-img"
                    src="https://www.vinmec.com/static/uploads/small_20201216_034658_190222_ca_chua_max_1800x1800_jpg_59dba7eb79.jpg"
                    alt="">
            </div>
            <div class="swiper-slide">
                <img
                    class="aside-img"
                    src="https://www.vinmec.com/static/uploads/20230519_145703_253706_Carbohydrate_max_1800x1800_jpg_288522dc00.jpg"
                    alt="">
            </div>
            <div class="swiper-slide">
                <img
                    class="aside-img"
                    src="https://www.vinmec.com/static/uploads/small_20191023_110959_903760_pectin_max_1800x1800_jpg_533e85d924.jpg"
                    alt="">
            </div>
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
            spaceBetween: 2, // Khoảng cách giữa các slide
            speed: 3000
        });
    });
</script>