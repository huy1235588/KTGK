class GameHover {
    constructor(productItemSelector, hoverElementSelector) {
        this.productItems = document.querySelectorAll(productItemSelector);
        this.hoverElement = document.querySelector(hoverElementSelector);
        this.hoverArrowLeft = this.hoverElement.querySelector('.arrow-left');
        this.hoverArrowRight = this.hoverElement.querySelector('.arrow-right');
        this.hoverContentContainer = this.hoverElement.querySelector('.product-hover-content');
        this.hoverTimeout = null;
        this.id = null;

        // Map lưu trữ các event handlers
        this.eventHandlers = new WeakMap();

        // Lưu trữ dữ liệu đã tải
        this.cachedData = new Map();

        this.init();
    }

    init() {
        this.productItems.forEach(item => {
            // Nếu chưa có event handler thì thêm mới
            if (!this.eventHandlers.has(item)) {
                const mouseEnterHandler = () => this.showHover(item);
                const mouseLeaveHandler = this.hideHover.bind(this);

                // Lưu event handler vào WeakMap
                this.eventHandlers.set(item, {
                    mouseEnter: mouseEnterHandler,
                    mouseLeave: mouseLeaveHandler
                });

                // Thêm sự kiện cho sản phẩm
                item.addEventListener('mouseenter', mouseEnterHandler);
                item.addEventListener('mouseleave', mouseLeaveHandler);
            }
        });
    }

    // Hàm debounce
    debounce(func, wait) {
        clearTimeout(this.hoverTimeout);
        this.hoverTimeout = setTimeout(func, wait);
    }

    // Hàm hiển thị hover khi rê chuột vào sản phẩm
    async showHover(item) {
        // Nếu đang hiển thị hover của sản phẩm khác thì ẩn đi
        this.debounce(async () => {
            this.hoverElement.classList.add('active');

            // Lấy thông tin sản phẩm
            const itemRect = item.getBoundingClientRect();
            const hoverElementRect = this.hoverElement.getBoundingClientRect();
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
            this.hoverElement.style.top = `${itemRect.top + window.scrollY}px`;
            this.hoverElement.style.left = `${left}px`;

            // Lấy id của sản phẩm
            this.id = item.dataset.id;

            // Hiển thị nội dung nếu đã có trong cache
            if (this.cachedData.has(this.id)) {
                this.hoverContentContainer.innerHTML = this.cachedData.get(this.id);
            } else {
                // Nếu chưa có, bắt đầu tải dữ liệu
                this.hoverContentContainer.innerHTML = '<div class="product-hover-content-loading">Loading...</div>';
                await this.loadData(this.id);
            }
        }, 100);
    }

    // Hàm tải dữ liệu sản phẩm
    async loadData(id) {
        try {
            const response = await fetch(`/api/product_hover.php?id=${id}`);
            const data = await response.text();
            this.cachedData.set(this.id, data);
            this.hoverContentContainer.innerHTML = data;
        } catch (error) {
            console.error('Error:', error);
            this.hoverContentContainer.innerHTML = `<div id="hover-product-${this.id}" class="product-hover-content-item">Error loading data</div>`;
        }
    }

    // Hàm ẩn hover khi rời chuột khỏi sản phẩm
    hideHover() {
        this.debounce(() => {
            this.hoverElement.classList.remove('active');
            const content = this.hoverContentContainer.querySelector(`#hover-product-${this.id}`);
            if (content) content.style.display = 'none';
        }, 100);
    }

    remove() {
        this.productItems.forEach(item => {
            const handlers = this.eventHandlers.get(item);
            if (handlers) {
                item.removeEventListener('mouseenter', handlers.mouseEnter);
                item.removeEventListener('mouseleave', handlers.mouseLeave);
                this.eventHandlers.delete(item);
            }
        });
    }
}
