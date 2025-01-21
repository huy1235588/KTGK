// Hàm xử lý sự kiện khi tìm kiếm sản phẩm
function searchbar() {
    const searchbarInput = document.querySelector('.searchbar-input');
    const buttonSearchbar = document.querySelector('.button-searchbar');
    const searchbarClearBtn = document.querySelector('.searchbar-clear-btn');

    // Xoá nội dung trong ô search
    searchbarClearBtn.addEventListener('click', () => {
        searchbarInput.value = '';
        searchbarClearBtn.style.opacity = 0;

        // Focus vào ô search
        searchbarInput.focus();
    });

    // Hiển thị gợi ý sản phẩm
    searchbarInput.addEventListener('input', async () => {
        const query = searchbarInput.value.trim();

        // khi nhấn nút search
        buttonSearchbar.addEventListener('click', () => {
            window.location.href = `search.php?q=${encodeURIComponent(query)}`;
        });

        if (query.length > 0) {
            searchbarClearBtn.style.opacity = 1;

            // Gọi API để lấy danh sách sản phẩm
            // const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);

        } else {
            searchbarClearBtn.style.opacity = 0;
        }
    });

    // Xử lý sự kiện khi focus vào ô search
    searchbarInput.addEventListener('keydown', (event) => {
        // Khi nhấn enter
        if (event.key === 'Enter') {
            event.preventDefault();

            // Chưa hover mục nào -> chuyển hướng đến trang tìm kiếm
            if (index === -1) {
                window.location.href = `search.php?q=${encodeURIComponent(searchbarInput.value)}`;
            }
            // Hover mục nào đó -> chuyển hướng tới liên kết của mục đó
            else {
                items[index].click();
            }
        }

        // Khi nhấn tab thì focus vào nút search
        else if (event.key === 'Tab') {
            event.preventDefault();
            dropdown.style.display = 'none';
            buttonSearchbar.focus();
        }
    });
}

// Hàm xử lý sự kiện khi chuyển đổi chế độ hiển thị
function switchDisplayMode() {
    const gridBtn = document.getElementById('gridBtn');
    const listBtn = document.getElementById('listBtn');
    const products = document.querySelector('.product');

    var gameHover = new GameHover('.product-item', '.product-hover');

    // Chuyển sang Grid
    gridBtn.addEventListener('click', () => {
        products.classList.remove('list');
        products.classList.add('grid');

        // Hiển thị hover khi chuyển sang chế độ Grid
        gameHover.init();

        // Thêm class active cho nút Grid và xoá class active của nút List
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');

        // Lưu chế độ hiển thị vào localStorage
        localStorage.setItem('displayMode', 'grid');
    });

    // Chuyển sang List
    listBtn.addEventListener('click', () => {
        // Thêm class active cho nút List và xoá class active của nút Grid
        products.classList.remove('grid');
        products.classList.add('list');

        // Ẩn hover khi chuyển sang chế độ List
        gameHover.remove();

        // Thêm class active cho nút List và xoá class active của nút Grid
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');

        // Lưu chế độ hiển thị vào localStorage
        localStorage.setItem('displayMode', 'list');
    });

    // Mặc định chế độ hiển thị là lưới
    // gridBtn.click();

    // Lấy chế độ hiển thị từ localStorage
    const displayMode = localStorage.getItem('displayMode');
    if (displayMode === 'list') {
        listBtn.click();
    }
}

// Hàm xử lý sự kiện khi tính toán rating
function calculateRating() {
    const productRating = document.querySelectorAll('.product-rating');

    productRating.forEach(productRating => {
        const productRatingStar = productRating.querySelector('.product-rating-stars-cover');
        const productRatingText = productRating.querySelector('.product-rating-text').textContent;

        // Tính toán width cho rating
        const rating = parseFloat(productRatingText) / 5 * 100;

        // Đặt width cho rating
        productRatingStar.style.width = `${rating}%`;
    });
}

// Hàm xử lý sự kiện khi thêm sản phẩm vào giỏ hàng
function addToCart() {
    const productItems = document.querySelectorAll('.product-item');

    /**
     * Gửi yêu cầu thêm sản phẩm vào giỏ hàng
     * @param {string} productId - ID của sản phẩm
     * @returns {Promise<string>} - Trả về thông báo thành công hoặc lỗi
     */
    async function addToCart(productId) {
        try {
            const response = await fetch('api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `productId=${productId}`
            });

            if (!response.ok) {
                throw new Error('Failed to add product to cart');
            }

            const data = await response.json();
            return data.message;
        } catch (error) {
            console.error(error);
            throw new Error('Failed to add product to cart');
        }
    }

    /**
    * Cập nhật giao diện nút giỏ hàng
    * @param {HTMLElement} button - Nút cần cập nhật
    * @param {boolean} inCart - Trạng thái sản phẩm trong giỏ hàng
    */
    function updateCartButton(button, inCart) {
        if (inCart) {
            button.classList.add('added');
        } else {
            button.classList.remove('added');
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
        const cartquantity = document.querySelector('.cart-quantity');
        cartquantity.textContent = parseInt(cartquantity.textContent) + count;
    }

    /**
    * Thực hiện hiệu ứng bay ảnh khi thêm sản phẩm vào giỏ hàng
    * @param {HTMLElement} productImage - Ảnh sản phẩm
    * @param {HTMLElement} cartIcon - Icon giỏ hàng
    * @returns {void}
    * @description Thực hiện hiệu ứng bay ảnh từ vị trí ảnh sản phẩm đến icon giỏ hàng
    * và rung icon giỏ hàng sau khi thêm sản phẩm vào giỏ hàng
    */
    function animateFlyingImage(productImage, cartIcon) {
        // Lấy vị trí của ảnh sản phẩm
        const imageRect = productImage.getBoundingClientRect();

        // Tạo bản sao hình ảnh để thực hiện hiệu ứng
        const flyingImage = productImage.cloneNode(true);
        flyingImage.classList.add('image-fly');

        // Đặt vị trí ban đầu của ảnh
        flyingImage.style.left = `${imageRect.left}px`;
        flyingImage.style.top = `${imageRect.top}px`;

        document.body.appendChild(flyingImage);

        // Lấy vị trí của icon giỏ hàng
        const cartRect = cartIcon.getBoundingClientRect();

        // Thực hiện hiệu ứng di chuyển ảnh
        flyingImage.animate([
            { top: imageRect.top + 'px', left: imageRect.left + 'px', width: imageRect.width + 'px', height: imageRect.height + 'px' },
            { top: (cartRect.top + 30) + 'px', left: (cartRect.left + 30) + 'px', width: '0', height: '0' }
        ], {
            duration: 1000,
            easing: 'ease-in-out',
        });

        // Hiệu ứng rung khi thêm vào giỏ hàng
        setTimeout(function () {
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            updateCartQuantity(1);

            cartIcon.classList.add('shake');
            setTimeout(function () {
                cartIcon.classList.remove('shake');
            }, 200);

        }, 1500);

        // Xóa ảnh sau khi thực hiện xong hiệu ứng
        setTimeout(function () {
            flyingImage.remove();
        }, 1000);
    }

    // Lặp qua từng sản phẩm
    productItems.forEach(productItem => {
        const addToCartBtn = productItem.querySelector('.cart-btn');

        // Kiểm tra xem người dùng đã đăng nhập chưa
        const isUser = sessionUsername !== '';
        if (!isUser) {
            addToCartBtn.classList.remove('hidden');
            return;
        }

        // Lấy ID của sản phẩm
        const productId = productItem.dataset.id;

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        const cartProductIds = sessionCart.map(item => item.productId);
        let isProductInCart = cartProductIds.includes(productId);

        // Cập nhật giao diện nút nếu sản phẩm đã trong giỏ hàng
        if (isProductInCart) {
            updateCartButton(addToCartBtn, true);
        }

        // Thêm sự kiện khi click vào nút thêm vào giỏ hàng
        addToCartBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            if (isProductInCart) {
                return;
            }
            else {
                try {
                    // Thêm sản phẩm vào giỏ hàng
                    addToCart(productId);

                    // Cập nhật giao diện nút giỏ hàng
                    updateCartButton(addToCartBtn, true);

                    // Thực hiện hiệu ứng bay ảnh
                    animateFlyingImage(productItem.querySelector('.product-img'), document.querySelector('.cart-link'));

                    // Cập nhật trạng thái sản phẩm trong giỏ hàng
                    isProductInCart = true;

                } catch (error) {
                    alert(`Failed to add product to cart: ${error.message}`);
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    searchbar();
    switchDisplayMode();
    calculateRating();
    addToCart();
});