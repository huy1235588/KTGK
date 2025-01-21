class GameHover {
    constructor(productItemSelector, hoverElementSelector) {
        this.productItems = document.querySelectorAll(productItemSelector);
        this.hoverElement = document.querySelector(hoverElementSelector);
        this.hoverArrowLeft = this.hoverElement.querySelector('.arrow-left');
        this.hoverArrowRight = this.hoverElement.querySelector('.arrow-right');
        this.hoverContentContainer = this.hoverElement.querySelector('.product-hover-content');
        this.hoverTimeout = null;
        this.id = null;

        this.init();
    }

    init() {
        // Hiển thị hover khi rê chuột vào sản phẩm
        this.productItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.showHover(item));
            item.addEventListener('mouseleave', () => this.hideHover());
        });

        // Xử lý sự kiện khi chuyển đổi chế độ hiển thị
        this.hoverElement.addEventListener('mouseenter', () => clearTimeout(this.hoverTimeout));
        this.hoverElement.addEventListener('mouseleave', () => this.hideHover());
    }

    // Hàm hiển thị hover khi rê chuột vào sản
    showHover(item) {
        // Nếu đang hiển thị hover của sản phẩm khác thì ẩn đi
        clearTimeout(this.hoverTimeout);

        this.hoverTimeout = setTimeout(() => {
            this.hoverElement.classList.add('active');

            // Lấy thông tin sản phẩm
            const itemRect = item.getBoundingClientRect();
            const hoverElementRect = this.hoverElement.getBoundingClientRect();
            // const top = itemRect.top + window.scrollY + itemRect.height / 2 - hoverElementRect.height / 2;
            const top = itemRect.top + window.scrollY;
            // const left = itemRect.right + window.scrollX + 10;
            let left = itemRect.right + window.scrollX + 10;

            // Nếu hover bị tràn ra khỏi viewport thì đặt lại vị trí
            if (left + hoverElementRect.width > window.innerWidth) {
                left = itemRect.left - hoverElementRect.width - 10;
                this.hoverArrowLeft.style.display = 'none';
                this.hoverArrowRight.style.display = 'block';
            } else {
                this.hoverArrowLeft.style.display = 'block';
                this.hoverArrowRight.style.display = 'none';
            }

            // Cập nhật vị trí của hover
            this.hoverElement.style.top = `${top}px`;
            this.hoverElement.style.left = `${left}px`;

            // Hiển thị nội dung
            this.id = item.dataset.id;
            const content = this.hoverContentContainer.querySelector(`#hover-product-${this.id}`);

            // Nếu đã có nội dung thì hiển thị lên
            if (content) {
                content.style.display = 'block';
            }
            // Nếu chưa có nội dung thì tải dữ liệu từ server
            else {
                this.hoverContentContainer.innerHTML += `<div class="product-hover-content-loading">Loading...</div>`;

                fetch(`/api/product_hover.php?id=${this.id}`)
                    .then(response => response.text())
                    .then(data => {
                        this.hoverContentContainer.innerHTML += data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.hoverContentContainer.innerHTML = `<div id="hover-product-${this.id}" class="product-hover-content-item">Error loading data</div>`;
                    })
                    .finally(() => {
                        // Xoá nội dung loading
                        const loading = this.hoverContentContainer.querySelector('.product-hover-content-loading');
                        if (loading) {
                            loading.remove();
                        }
                    });
            }
        }, 100);
    }

    // Hàm ẩn hover khi rời chuột khỏi sản phẩm
    hideHover() {
        clearTimeout(this.hoverTimeout);
        this.hoverElement.classList.remove('active');
        this.hoverContentContainer.querySelector(`#hover-product-${this.id}`).style.display = 'none';
    }
}