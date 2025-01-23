// Hàm xử lý sự kiện khi tìm kiếm sản phẩm
function searchbar() {
    const searchbarInput = document.querySelector('.searchbar-input');
    const buttonSearchbar = document.querySelector('.button-searchbar');
    const searchbarClearBtn = document.querySelector('.searchbar-clear-btn');

    // Lấy query từ URL
    const url = new URL(window.location.href);
    const query = url.searchParams.get('q') || '';

    // Điền nội dung search từ query vào ô search
    searchbarInput.value = query;

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

            url.searchParams.set('q', query);

        } else {
            searchbarClearBtn.style.opacity = 0;
        }
    });

    // Xử lý sự kiện khi focus vào ô search
    searchbarInput.addEventListener('keydown', (event) => {
        // Khi nhấn enter
        if (event.key === 'Enter') {
            event.preventDefault();

            // Chuyển hướng đến trang search
            window.location.href = `search.php?q=${encodeURIComponent(searchbarInput.value)}`;
        }

        // Khi nhấn tab thì focus vào nút search
        else if (event.key === 'Tab') {
            event.preventDefault();
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
        const isUser = sessionUsername !== '' && sessionRole === 'user';
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

// Hàm xử lý sự kiện khi sắp xếp sản phẩm
function sortProducts() {
    // Tạo dropdown chọn sắp xếp
    const sortSelect = document.getElementById("sortSelect");
    const sortOptions = [
        {
            value: {
                key: "releaseDate",
                order: "desc"
            }, label: "Newest"
        },
        {
            value: {
                key: "releaseDate",
                order: "asc"
            }, label: "Oldest"
        },
        {
            value: {
                key: "price",
                order: "desc"
            }, label: "Price: High to Low"
        },
        {
            value: {
                key: "price",
                order: "asc"
            }, label: "Price: Low to High"
        },
        {
            value: {
                key: "rating",
                order: "desc"
            }, label: "Rating: High to Low"
        },
        {
            value: {
                key: "rating",
                order: "asc"
            }, label: "Rating: Low to High"
        },
        {
            value: {
                key: "title",
                order: "asc"
            }, label: "Title: A to Z"
        },
        {
            value: {
                key: "title",
                order: "desc"
            }, label: "Title: Z to A"
        },
    ];

    // Tìm tùy chọn sắp xếp tương ứng với tham số `sort` và `order`
    const selectedOption = sortOptions.find(option => option.value.key === sort && option.value.order === order.toLowerCase());

    // Tạo dropdown chọn sắp xếp
    new Select(sortSelect, sortOptions, selectedOption.label, {
        value: selectedOption.value,
        width: "200px",
        onChange: function (value) {
            sortProducts(value.key, value.order);
        }
    });

    // Hàm sắp xếp sản phẩm
    function sortProducts(key, order) {
        // Tạo một URL mới từ đường dẫn hiện tại
        const url = new URL(window.location.href);

        // Cập nhật tham số `sort` và `order` trên URL
        url.searchParams.set('sort', key);
        url.searchParams.set('order', order);

        // Điều hướng đến URL mới
        window.location.href = url.toString(); // Sử dụng `toString` để chuyển đổi URL thành chuỗi
    }
}

// Hàm xử lý sự kiện khi chuyển trang
function changePage() {
    new Pagination({
        container: '#pagination',
        totalPages: totalPages,
        currentPage: currentPage,
        onPageChange: (page) => {
            goToPage(page);
        }
    });

    // Hàm xử lý khi chuyển trang
    function goToPage(page) {
        // Tạo một URL mới từ đường dẫn hiện tại
        const url = new URL(window.location.href);

        // Cập nhật tham số `page` trên URL
        url.searchParams.set('page', page);

        // Điều hướng đến URL mới
        window.location.href = url.toString(); // Sử dụng `toString` để chuyển đổi URL thành chuỗi
    }
}

// Hàm xử lý sự kiện khi lọc sản phẩm
function filter() {
    const filterContainer = document.querySelector('.filters-container');
    const filterItems = document.querySelectorAll('.filter-select');

    filterItems.forEach(filterItem => {
        const filterHeader = filterItem.querySelector('.filter-header');
        const filterBody = filterItem.querySelector('.filter-body');

        // Xử lý sự kiện khi click vào option
        const search = filterBody.querySelector('.filter-select-search input');
        if (search) {
            // Xử lý sự kiện khi nhập vào ô search
            search.addEventListener('input', () => {
                const value = search.value.trim().toUpperCase();

                // Lặp qua từng option để tìm kiếm
                filterBody.querySelectorAll('.filter-scrollable label').forEach(option => {
                    const text = option.textContent || option.innerText;
                    const match = text.toUpperCase().includes(value);

                    // Hiển thị hoặc ẩn option tùy theo kết quả tìm kiếm
                    if (match) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });
        }

        // Khởi tạo trạng thái mặc định
        filterBody.style.overflow = 'hidden';
        filterBody.style.height = '0px';

        // Xử lý sự kiện khi click vào header
        filterHeader.addEventListener('click', () => {
            const isOpen = filterItem.classList.toggle('open');

            if (isOpen) {
                filterBody.style.height = `${filterBody.scrollHeight}px`;
                filterBody.style.overflow = 'hidden'; // Đảm bảo không xuất hiện scroll trong quá trình mở
            } else {
                filterBody.style.height = '0px';
            }

            // Đóng các filter khác khi mở filter mới
            filterItems.forEach(otherItem => {
                if (otherItem !== filterItem && otherItem.classList.contains('open')) {
                    otherItem.classList.remove('open');
                    const otherBody = otherItem.querySelector('.filter-body');
                    otherBody.style.height = '0px';
                }
            });
        });

        // Xử lý sự kiện khi kết thúc transition
        filterBody.addEventListener('transitionend', () => {
            if (!filterItem.classList.contains('open')) {
                filterBody.style.overflow = 'hidden';
            } else {
                filterBody.style.removeProperty('overflow');
            }
        });
    });

    // Tạo dropdown chọn số lượng sản phẩm
    document.querySelector("#js-input-rating").addEventListener("input", function () {
        document.querySelector("#js-value-rating").textContent = this.value
    });
    // Tạo dropdown chọn giá sản phẩm
    document.querySelector("#js-input-discount").addEventListener("input", function () {
        const e = this.value
            , s = document.querySelector("#js-value-discount");
        s.textContent = e > 0 || s.dataset.zero ? `\u2265${e}` : `>${e}`
    });

    // Lấy input giá trị release tối thiểu
    const minReleaseInput = document.querySelector('input[name="min_release"]');
    if (minReleaseInput) {
        // Lấy input giá trị release tối đa
        const maxReleaseInput = document.querySelector('input[name="max_release"]');
        if (minReleaseInput.value) {
            maxReleaseInput.setAttribute("min", minReleaseInput.value);
        }
        // Thêm sự kiện thay đổi cho input release tối thiểu
        minReleaseInput.addEventListener("change", function () {
            maxReleaseInput.setAttribute("min", this.value || minReleaseInput.getAttribute("min"));
        });
    }
}

/**
 * Xử lý sự kiện khi người dùng gửi biểu mẫu lọc.
 * - Ngăn chặn hành vi mặc định của biểu mẫu.
 * - Thu thập các giá trị được chọn từ các bộ lọc (nền tảng, thể loại, thẻ, tính năng, ngôn ngữ).
 * - Tạo các trường ẩn cho các giá trị lọc đã chọn.
 * - Thêm các trường ẩn cho các giá trị sắp xếp, thứ tự và trang hiện tại.
 * - Tạo đối tượng FormData từ biểu mẫu và chuyển đổi thành URLSearchParams.
 * - Xóa tham số 'category' khỏi URL.
 * - Sắp xếp các tham số trong URL.
 * - Chuyển hướng đến URL tìm kiếm mới với các tham số đã cập nhật.
 */
function handleFilterSubmit() {
    const filterForm = document.getElementById('js-filters');

    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const inputSelectors = [
            ".fancy-price-input",
            ".fancy-date-input",
        ];
        inputSelectors.forEach(function (selector) {
            filterForm.querySelectorAll(selector).forEach(function (input) {
                input.value <= 0 && (input.disabled = true);
            });
        });

        // Thu thập các giá trị được chọn từ các bộ lọc
        const filterIds = [
            'js-select-platform',
            'js-select-genre',
            'js-select-tag',
            'js-select-feature',
            'js-select-language'
        ];

        // Thêm các trường ẩn cho các giá trị lọc đã chọn
        filterIds.forEach((filterId) => {
            const filterElement = document.getElementById(filterId);
            const selectedValues = Array.from(filterElement.querySelectorAll('.filter-scrollable input:checked'))
                .map(input => input.value);

            if (selectedValues.length > 0) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = filterId.replace('js-select-', '');
                hiddenInput.value = selectedValues.join(',');
                filterForm.appendChild(hiddenInput);
            }
        });

        // Thêm các tham số sắp xếp và phân trang
        const filterValues = [
            { name: 'q', value: search },
            { name: 'sort', value: sort },
            { name: 'order', value: order },
            { name: 'page', value: currentPage }
        ];

        filterValues.forEach(({ name, value }) => {
            if (value) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = name;
                hiddenInput.value = value;
                filterForm.appendChild(hiddenInput);
            }
        });

        // Tạo đối tượng FormData từ biểu mẫu và chuyển đổi thành URLSearchParams
        const formData = new FormData(filterForm);
        const urlParams = new URLSearchParams(formData);

        // Xóa tham số 'category' khỏi urlParams
        urlParams.delete('category');
        // Sắp xếp các tham số trong urlParams
        urlParams.sort();

        console.log(urlParams.toString());

        // Chuyển hướng đến URL mới với các tham số đã cập nhật
        window.location.href = `search.php?${urlParams.toString()}`;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    searchbar();
    switchDisplayMode();
    calculateRating();
    addToCart();
    sortProducts();
    changePage();
    filter();
    handleFilterSubmit();
});