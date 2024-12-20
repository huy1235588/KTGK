document.addEventListener('DOMContentLoaded', () => {
    // Cấu hình Swiper thu nhỏ
    const swiperThumbs = new Swiper('.swiper-thumbs', {
        slidesPerView: 5, // Hiển thị 5 hình thu nhỏ
        centeredSlides: false, // Giữa các hình thu nhỏ
        slideToClickedSlide: true, // Cho phép nhấp vào hình thu nhỏ để chuyển đến slide chính
        spaceBetween: 10,
    });

    // Swiper chính
    const swiperProductImage = new Swiper('.swiper.product-img', {
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

    // Sản phẩm liên quan
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