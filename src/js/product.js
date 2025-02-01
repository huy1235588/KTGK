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
     *    System requirements tabs
     * 
    ***************************************/
    const tabsSystemRequirements = document.querySelectorAll('.sysreq_tab');
    const contentsSystemRequirements = document.querySelectorAll('.sysreq_contents');

    tabsSystemRequirements.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            tabsSystemRequirements.forEach((tab) => tab.classList.remove('active'));
            contentsSystemRequirements.forEach((content) => content.classList.remove('active'));

            tab.classList.add('active');
            contentsSystemRequirements[index].classList.add('active');
        });
    });

    /**************************************
     * 
     *    Read more button
     * 
    ***************************************/
    const j = document.querySelectorAll('.game_page_autocollapse_ctn');

    j.forEach((element) => {
        const button = element.querySelector('.game_page_autocollapse_readmore');
        const content = element.querySelector('.game_page_autocollapse');

        button.addEventListener('click', () => {
            element.classList.add('expanded');
            element.classList.remove('collapsed');

            content.style.maxHeight = 'none';
        });
    });

    /**************************************
    * 
    *    Add to cart
    * 
   ***************************************/
    /**
    * Gửi yêu cầu thêm sản phẩm vào giỏ hàng
    * @param {string} productId - ID của sản phẩm
    * @returns {Promise<string>} - Trả về thông báo thành công hoặc lỗi
    */
    async function addToCart(productId) {
        const response = await fetch('api/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            // Gửi dữ liệu productId và userId
            body: `productId=${productId}`
        });

        if (!response.ok) {
            throw new Error('Failed to add product to cart');
        }

        const data = await response.json();
        return data.message;
    }

    /**
     * Cập nhật giao diện nút giỏ hàng
     * @param {HTMLElement} button - Nút cần cập nhật
     * @param {boolean} inCart - Trạng thái sản phẩm trong giỏ hàng
     */
    function updateCartButton(button, inCart) {
        if (inCart) {
            button.textContent = 'VIEW IN CART';
            button.classList.add('view-cart-btn');
        } else {
            button.textContent = 'ADD TO CART';
            button.classList.remove('view-cart-btn');
        }
    }

    /**
    * Cập nhật số lượng sản phẩm trong giỏ hàng
    * @param {number} count - Số lượng sản phẩm cần cập nhật
    * @returns {void}
    * @description Cập nhật số lượng sản phẩm trong giỏ hàng trên icon giỏ hàng
    * bằng cách cộng thêm số lượng sản phẩm mới thêm vào
    * hoặc trừ đi số lượng sản phẩm đã xóa khỏi giỏ hàng
    * @example updateCartQuantity(1) // Thêm 1 sản phẩm vào giỏ hàng
    * @example updateCartQuantity(-1) // Xóa 1 sản phẩm khỏi giỏ hàng
    * @example updateCartQuantity(0) // Xóa tất cả sản phẩm khỏi giỏ hàng
    * @example updateCartQuantity(5) // Thêm 5 sản phẩm vào giỏ hàng
    */
    function updateCartQuantity(count) {
        const cartQuantity = document.querySelector('.cart-quantity');
        cartQuantity.textContent = parseInt(cartQuantity.textContent) + count;
    }

    function initAddToCart() {
        const cartBtn = document.querySelector('.cart-btn');
        if (!cartBtn) return; // Kiểm tra nếu nút không tồn tại

        const productId = cartBtn.dataset.id;
        const cartProductIds = sessionCart.map(item => item.productId);

        let isProductInCart = cartProductIds.includes(productId);

        // Cập nhật giao diện nút nếu sản phẩm đã trong giỏ hàng
        if (isProductInCart) {
            updateCartButton(cartBtn, true);
        }

        // Xử lý sự kiện click
        cartBtn.addEventListener('click', () => {
            // Vô hiệu hóa nút sau khi thêm vào giỏ hàng
            cartBtn.disabled = true;
            
            if (isProductInCart) {
                window.location.href = 'cart.php';
            } else {
                addToCart(productId).then(message => {
                    // Hiển thị thông báo
                    setNotification(message, 'success');
                    updateCartButton(cartBtn, true);
                    isProductInCart = true;

                    // Cập nhật số lượng sản phẩm trong giỏ hàng
                    updateCartQuantity(1);

                }).catch(error => {
                    setNotification(error.message, 'error');
                }).finally(() => {
                    cartBtn.disabled = false;
                });
            }
        });
    }

    initAddToCart();
});