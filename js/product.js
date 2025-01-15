document.addEventListener('DOMContentLoaded', () => {
    /**************************************
     *  
     *   Swiper sản phẩm
     * 
     *************************************/
    const swiperThumbs = new Swiper('.swiper-thumbs', {
        slidesPerView: 5, // Hiển thị 5 hình thu nhỏ
        centeredSlides: false, // Giữa các hình thu nhỏ
        slideToClickedSlide: true, // Cho phép nhấp vào hình thu nhỏ để chuyển đến slide chính
        spaceBetween: 5,
        scrollbar: {
            el: '.swiper-scrollbar', // Định nghĩa thanh trượt
            draggable: true, // Cho phép kéo thả thanh trượt
        },
    });

    // Swiper chính
    const swiperProductMedia = new Swiper('.swiper.product-media', {
        loop: true, // Nối tiếp các slide
        autoplay: false,
        slidesPerView: 1, // Hiển thị 1 slide mỗi lần
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: swiperThumbs, // Liên kết với Swiper thu nhỏ
        },
        spaceBetween: 0, // Khoảng cách giữa các slide
    });

    // Youtube API ready
    let youtubePlayers = {};

    function onYouTubeIframeAPIReady() {
        const iframes = document.querySelectorAll('.youtube-video');
        iframes.forEach((iframe, index) => {
            const videoId = iframe.getAttribute('data-video-id');
            youtubePlayers[index] = new YT.Player(iframe, {
                events: {
                    onStateChange: (event) => onYouTubePlayerStateChange(event, index),
                },
            });
        });
    };

    // Xử lý sự kiện thay đổi trạng thái video youtube 
    function onYouTubePlayerStateChange(event, index) {
        if (event.data === YT.PlayerState.PLAYING) {
            swiperProductMedia.autoplay.stop(); // Dừng autoplay khi video phát
        } else if (event.data === YT.PlayerState.ENDED) {
            swiperProductMedia.slideNext(); // Chuyển slide sau khi video phát xong
            swiperProductMedia.autoplay.start(); // Bật lại autoplay
        }
    }

    // Hàm để phát video nếu slide chứa video
    function handleVideoAutoplay() {
        const activeSlide = document.querySelector('.swiper.product-media .swiper-slide-active');
        const video = activeSlide.querySelector('video');
        const youtube = activeSlide.querySelector('iframe.youtube-video');

        if (video) {
            // Tạm dừng autoplay
            swiperProductMedia.autoplay.stop();

            // Mở âm thanh
            video.muted = false;

            // Phát video
            video.play();

            // Khi video kết thúc, tiếp tục autoplay
            video.onended = () => {
                swiperProductMedia.slideNext(); // Chuyển sang slide tiếp theo
                swiperProductMedia.autoplay.start(); // Bật lại autoplay
            };
        }
        else if (youtube) {
            const playerIndex = Array.from(document.querySelectorAll('.youtube-video')).indexOf(youtube);

            // Mở âm thanh
            youtubePlayers[playerIndex].unMute();

            // Phát video YouTube
            youtubePlayers[playerIndex].playVideo(); 
        }
        else {
            // Nếu không có video, đảm bảo autoplay vẫn hoạt động
            swiperProductMedia.autoplay.start();
        }
    }

    // Dừng tất cả video khi chuyển slide
    swiperProductMedia.on('slideChangeTransitionStart', () => {
        const allVideos = document.querySelectorAll('.swiper.product-media video');
        allVideos.forEach((video) => {
            video.pause();
            video.currentTime = 0; // Reset thời gian về 0
        });
        
        // Dừng tất cả video youtube
        Object.values(youtubePlayers).forEach((player) => {
            if (player.getPlayerState() === YT.PlayerState.PLAYING) {
                player.pauseVideo();
            }
        });
    });

    // Lắng nghe sự kiện `slideChangeTransitionEnd`
    swiperProductMedia.on('slideChangeTransitionEnd', () => {
        handleVideoAutoplay();
    });

    // Gọi hàm khi Youtube API sẵn sàng
    window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReady;

    /**************************************
     * 
     *    Swiper sản phẩm liên quan
     * 
    ***************************************/
    const swiperProductRelate = new Swiper('.product-relate', {
        loop: false, // Nối tiếp các slide
        autoplay: false,
        slidesPerView: 4, // Hiển thị 5 slide mỗi lần
        spaceBetween: 10, // Khoảng cách giữa các slide
    });

    const decrementButton = document.getElementById("decrement");
    const incrementButton = document.getElementById("increment");
    const quantityInput = document.getElementById("quantity");

    decrementButton.addEventListener("click", () => {
        let currentValue = parseInt(quantityInput.value) || 0;
        if (currentValue > parseInt(quantityInput.min)) {
            quantityInput.value = currentValue - 1;
        }
    });

    incrementButton.addEventListener("click", () => {
        let currentValue = parseInt(quantityInput.value) || 0;
        if (currentValue < parseInt(quantityInput.max)) {
            quantityInput.value = currentValue + 1;
        }
    });
});