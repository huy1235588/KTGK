document.addEventListener('DOMContentLoaded', () => {
    /**************************************
     *  
     *   Swiper hot product
     * 
     *************************************/
    const swiperHotGame = new Swiper('.swiper-hot-game', {
        slidesPerView: 1,
        spaceBetween: 10,
        autoplay: false,
        navigation: {
            nextEl: '.swiper-button-next.hot-game-next',
            prevEl: '.swiper-button-prev.hot-game-prev',
        },
        loop: true,
    });

    // Hàm để phát video nếu slide chứa video
    function handleVideoAutoplay() {
        const activeSlide = document.querySelector('.swiper-hot-game .swiper-slide-active');
        const video = activeSlide.querySelector('video');

        if (video) {
            // Dừng autoplay
            swiperHotGame.autoplay.stop();

            // Phát video
            video.play();

            // Khi video kết thúc, tiếp tục autoplay
            video.onended = () => {
                swiperHotGame.slideNext(); // Chuyển sang slide tiếp theo
                swiperHotGame.autoplay.start(); // Bật lại autoplay
            };
        }
        else {
            // Nếu không có video, đảm bảo autoplay vẫn hoạt động
            swiperHotGame.autoplay.start();
        }
    }

    // Dừng tất cả video khi chuyển slide
    swiperHotGame.on('slideChangeTransitionStart', () => {
        const allVideos = document.querySelectorAll('.swiper-hot-game video');
        allVideos.forEach((video) => {
            video.pause();
            video.currentTime = 0; // Reset thời gian về 0
        });
    });

    // Lắng nghe sự kiện `slideChangeTransitionEnd`
    swiperHotGame.on('slideChangeTransitionEnd', () => {
        console.log('slideChangeTransitionEnd');
        handleVideoAutoplay();
    });

    /**************************************
     * 
     *  Rê chuột vào ảnh screenshot thì thay ảnh chính
     * 
     * *************************************/
    // Khi rê chuột vào ảnh screenshot thì thay ảnh chính
    const productLinks = document.querySelectorAll('.hot-game .product-link');
    productLinks.forEach(function (link) {
        const productImage = link.querySelectorAll('.product-img');
        const productImageHeader = link.querySelector('.product-img-header');
        const productScreenshots = link.querySelectorAll('.screenshot');

        // Khi rê chuột vào ảnh screenshot thì thay ảnh chính
        productScreenshots.forEach(function (screenshot) {
            screenshot.addEventListener('mouseover', function () {
                productImage.forEach(function (img) {
                    img.classList.remove('active');
                });

                const img = link.querySelector(`.product-img[src="${screenshot.src}"]`);
                img.classList.add('active');

                // Ẩn ảnh header
                productImageHeader.style.display = 'none';
            });

            // Khi rê chuột ra khỏi ảnh screenshot thì hiện ảnh chính
            screenshot.addEventListener('mouseout', function () {
                productImage.forEach(function (img) {
                    img.classList.remove('active');
                });

                productImageHeader.classList.add('active');

                // Hiện ảnh header
                productImageHeader.style.display = 'block';
            });
        });
    });
});