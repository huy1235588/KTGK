class Pagination {
    // Khởi tạo đối tượng Pagination với các tham số container, totalPages, currentPage và onPageChange
    constructor({ container, totalPages, currentPage, onPageChange }) {
        this.container = document.querySelector(container);
        this.totalPages = totalPages;
        this.currentPage = currentPage;
        this.onPageChange = onPageChange;

        this.render();
    }

    // Hàm render để vẽ các nút phân trang
    render() {
        if (this.totalPages < 1) return;

        this.container.innerHTML = '';
        const range = 2;

        // Nút Previous
        const prevButton = document.createElement('a');
        prevButton.className = 'prev';
        prevButton.innerHTML = '&lt;';
        if (this.currentPage > 1) {
            prevButton.addEventListener('click', () => this.changePage(this.currentPage - 1));
        } else {
            prevButton.classList.add('disabled');
        }
        this.container.appendChild(prevButton);

        // Trang đầu tiên và dấu ba chấm
        if (this.currentPage > range + 1) {
            this.container.appendChild(this.createPageButton(1));
            this.container.appendChild(this.createEllipsis());
        }

        // Phạm vi trang
        for (let i = Math.max(1, this.currentPage - range); i <= Math.min(this.totalPages, this.currentPage + range); i++) {
            this.container.appendChild(this.createPageButton(i, i === this.currentPage));
        }

        // Trang cuối cùng và dấu ba chấm
        if (this.currentPage < this.totalPages - range) {
            this.container.appendChild(this.createEllipsis());
            this.container.appendChild(this.createPageButton(this.totalPages));
        }

        // Nút Next
        const nextButton = document.createElement('a');
        nextButton.className = 'next';
        nextButton.innerHTML = '&gt;';
        if (this.currentPage < this.totalPages) {
            nextButton.addEventListener('click', () => this.changePage(this.currentPage + 1));
        } else {
            nextButton.classList.add('disabled');
        }
        this.container.appendChild(nextButton);
    }

    // Hàm tạo nút phân trang
    createPageButton(page, isActive = false) {
        const button = document.createElement('a');
        button.innerHTML = page;
        button.className = isActive ? 'active' : '';
        if (!isActive) {
            button.addEventListener('click', () => this.changePage(page));
        }
        return button;
    }

    // Hàm tạo dấu ba chấm
    createEllipsis() {
        const span = document.createElement('span');
        span.className = 'ellipsis';
        span.innerHTML = '...';
        return span;
    }

    // Hàm thay đổi trang
    changePage(newPage) {
        this.currentPage = newPage;
        this.onPageChange(newPage);
        this.render();
    }
}