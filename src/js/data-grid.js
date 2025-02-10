class DataGrid {
    constructor(containerId, columns, apiEndpoint, options = {}) {
        this.container = document.getElementById(containerId);
        this.columns = columns;
        this.orderBy = options.orderBy || columns[0].key;
        this.sortDirection = options.sortDirection || 'asc';
        this.currentSort = { column: this.orderBy, direction: this.sortDirection };
        this.apiEndpoint = apiEndpoint;
        this.data = [];
        this.filteredData = []; // Dữ liệu đã được lọc
        this.page = 1;
        this.rowsPerPage = options.rowsPerPage || 5;
        this.rowsPerPageOptions = options.rowsPerPageOptions || [5, 10, 25, 50];
        this.batchSize = options.batchSize || 25;
        this.totalLoaded = 0; // Số lượng sản phẩm đã tải
        this.totalPage = 0; // Tổng số trang
        this.total = 0; // Tổng số sản phẩm
        this.searchQuery = ''; // Từ khóa tìm kiếm
        this.loading = false;
        this.onRowClick = options.onRowClick || function () { };

        this.init();
    }

    async init() {
        await this.loadData(0); // Tải dữ liệu ban đầu
        this.container.innerHTML = `
            ${this.renderSearch()}
            <div id="data-grid-table-container" class="data-grid-table-container"></div>        
        `;
        this.render();
    }

    // Tải dữ liệu từ API
    async loadData(offset = 0, sort = this.orderBy, order = this.sortDirection) {
        if (this.loading) return; // Ngăn việc gọi API khi đang tải dữ liệu
        this.loading = true;

        try {
            // Nếu cột hoặc hướng sort thay đổi thì reset dữ liệu
            if (this.currentSort.column !== sort || this.currentSort.direction !== order) {
                this.data = [];
                this.totalLoaded = 0;
                this.page = 1;
                this.currentSort.column = sort;
                this.currentSort.direction = order;
            }

            // Gọi API để lấy dữ liệu
            const response = await fetch(
                `${this.apiEndpoint}`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        offset,
                        limit: this.batchSize,
                        sort,
                        order,
                        q: this.searchQuery,
                        columns: this.columns.map(col => col.key),
                    }),
                }
            );

            // Kiểm tra xem có lỗi không
            if (!response.ok) {
                throw new Error('Không thể tải dữ liệu');
            }

            // Lấy dữ liệu từ API
            const { data: products, total } = await response.json();
            // Thêm dữ liệu vào mảng data
            this.data.push(...products);

            // Tăng số lượng sản phẩm đã tải
            this.totalLoaded += products.length;

            // Tính toán tổng số trang
            this.total = total;
            this.totalPage = Math.ceil(total / this.rowsPerPage);

        } catch (error) {
            console.error("Lỗi khi tải dữ liệu:", error);
        } finally {
            this.loading = false;
        }
    }

    // Lọc dữ liệu
    filterData() {
        if (this.searchQuery.trim() === "") {
            this.filteredData = [...this.data];
        } else {
            const lowerCaseQuery = this.searchQuery.toLowerCase();
            this.filteredData = this.data.filter(row =>
                this.columns.some(col =>
                    String(row[col.key] || "")
                        .toLowerCase()
                        .includes(lowerCaseQuery)
                )
            );
        }
        this.page = 1; // Reset về trang đầu tiên
        this.render();
    }

    // Thêm sự kiện sort
    async attachSortHandlers() {
        const headers = this.container.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.addEventListener('click', async () => {
                const column = header.dataset.sort;
                if (this.orderBy === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.orderBy = column;
                    this.sortDirection = 'asc';
                }

                // Thêm ?sort=column&order=asc vào URL
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.orderBy);
                url.searchParams.set('order', this.sortDirection);
                window.history.pushState({}, '', url);

                // Reset lại dữ liệu
                await this.loadData(0, this.orderBy, this.sortDirection)
                this.render();
            });
        });
    }

    // Xử lý tìm kiếm
    async handleSearch(query) {
        this.searchQuery = query.target.value;
        this.data = []; // Xóa dữ liệu hiện tại
        this.totalLoaded = 0;
        this.page = 1;
        // Gọi lại API để tìm kiếm
        await this.loadData(0, this.orderBy, this.sortDirection);
        this.render();
    }

    // Đi đến trang cụ thể
    async goToPage(page) {
        const totalNeeded = page * this.rowsPerPage;

        // Nếu số lượng sản phẩm cần tải lớn hơn số lượng sản phẩm đã tải 
        // và số lượng sản phẩm đã tải nhỏ hơn tổng số sản phẩm
        if (totalNeeded > this.totalLoaded && this.totalLoaded < this.total) {
            const offset = this.totalLoaded;
            await this.loadData(offset, this.orderBy, this.sortDirection);
        }

        this.page = page;
        this.render();
    }

    // Hiển thị dữ liệu
    render() {
        this.container.querySelector('.data-grid-table-container').innerHTML = `
            ${this.renderTable()}
            <div class="data-grid-footer">
            ${this.renderRowsPerPage()}
            ${this.renderPagination()}
            </div>
        `;
        this.addEventListeners();
    }

    // Hiển thị ô tìm kiếm
    renderSearch() {
        return `
            <div class="search-container">
                <div class="search-input-container">
                    <span class="search-icon">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="searchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                        </svg>
                    </span>
                    <input
                        class="data-grid-search"
                        type="text"
                        placeholder="Search..."
                        value="${this.searchQuery}"
                    />
                </div>

                <button class="button-clear"
                    type="button"
                    tabindex="0" type="button">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z"></path>
                    </svg>
                </button>
            </div>
        `;
    }

    // Hiển thị tiêu đề cột
    renderTh() {
        return this.columns.map(col => `
            <th
                ${col.sortAble !== false ? `data-sort="${col.key}"` : ''}
                style="${col.style || ''};${col.width ? `width: ${col.width}` : ''}"
            >
                ${col.label ? col.label : ''}
                ${col.sortAble !== false ? `
                    <span class="sort-icon ${this.orderBy === col.key ? this.sortDirection : ''}">
                    </span>
                ` : ''}
                </th>
        `).join('');
    }

    // Hiển thị dữ liệu hàng
    renderTd(row) {
        return this.columns
            .map(col => {
                // Kiểm tra xem có renderCell không
                if (col.renderCell) {
                    return `<td style="${col.width ? `width: ${col.width}` : ''}; ${col.style || ''}">
                        <div class="data-grid-cell" style="${col.style || ''};">
                            ${col.renderCell(row[col.key], row)}
                        </div>
                    </td>`;
                }
                // Mặc định hiển thị dữ liệu
                return `<td style="${col.width ? `width: ${col.width}` : ''}; ${col.style || ''}">
                    <div class="data-grid-cell" style="${col.style || ''};">
                        ${row[col.key] || ''}
                    </div>
                </td>`;
            })
            .join('');
    }

    // Hiển thị bảng dữ liệu
    renderTable() {
        const start = (this.page - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;
        const rows = this.data.slice(start, end);

        return `
            <table class="data-grid-table">
                <thead>
                    <tr>
                        ${this.renderTh()}
                    </tr>
                </thead>
                <tbody>
                    ${rows.map(row => `
                        <tr class="data-grid-row" data-id="${row.id}">
                            ${this.renderTd(row)}
                        </tr>`).join('')}
                </tbody>
            </table>
        `;
    }

    // Hiển chọn số dòng trên mỗi trang
    renderRowsPerPage() {
        return `
            <div class="data-grid-rows-per-page">
                <label for="rowsPerPage">Rows per page:</label>
                <select id="rowsPerPage" value="${this.rowsPerPage}">
                    ${this.rowsPerPageOptions.map(option => `
                        <option value="${option}" ${option === this.rowsPerPage ? 'selected' : ''}>
                            ${option}
                        </option>
                    `).join('')}

                </select>
            </div>
        `;
    }

    // Hiển thị phân trang
    renderPagination() {
        const totalPages = Math.ceil(this.total / this.rowsPerPage);
        return `
            <div class="data-grid-pagination">
                <button ${this.page === 1 ? 'disabled' : ''} data-action="prev">Previous</button>
                <span>Page ${this.page} of ${totalPages}</span>
                <button ${this.page === totalPages ? 'disabled' : ''
            } data-action="next">Next</button>
            </div>
        `;
    }

    // Thêm sự kiện click vào nút phân trang
    addEventListeners() {
        // Lấy ra input tìm kiếm và nút xóa nội dung tìm kiếm
        const searchInput = this.container.querySelector('.data-grid-search');
        const clearButton = this.container.querySelector('.button-clear');

        // Hàm debounce để giảm số lần gọi API khi người dùng nhập nhanh
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), timeout);
            };
        }
        // Gọi hàm debounce
        const debouncedHandleSearch = debounce(this.handleSearch.bind(this), 500);
        searchInput.addEventListener('input', (e) => {
            // Hiện nút xóa nội dung tìm kiếm khi có nội dung
            clearButton.style.display = e.target.value ? 'flex' : 'none';

            debouncedHandleSearch(e);
        });

        // Thêm class khi focus vào input tìm kiếm
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.parentElement.classList.add('focus');
        });

        // Xóa class khi blur khỏi input tìm kiếm
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.parentElement.classList.remove('focus');
        });

        // Thêm sự kiện click vào nút xóa nội dung tìm kiếm
        clearButton.addEventListener('click', () => {
            searchInput.value = '';
            this.handleSearch({ target: searchInput });

            // Focus vào input tìm kiếm
            searchInput.focus();
            // Ẩn nút xóa nội dung tìm kiếm
            clearButton.style.display = 'none';
        });

        // Thêm sự kiện click vào hàng
        this.container
            .querySelectorAll('.data-grid-row')
            .forEach(row => {
                row.addEventListener('click', e => {
                    const id = e.currentTarget.getAttribute('data-id');
                    this.onRowClick(id);
                });
            });

        // Thêm sự kiện click vào nút phân trang
        this.container
            .querySelectorAll('.data-grid-pagination button')
            .forEach(button => {
                button.addEventListener('click', async e => {
                    const action = e.target.getAttribute('data-action');
                    if (action === 'prev' && this.page > 1) {
                        this.page--;
                    }
                    if (action === 'next') {
                        await this.goToPage(this.page + 1);
                    }
                    this.render();
                });
            });

        // Thêm sự kiện khi chọn số dòng trên mỗi trang
        this.container
            .querySelector('#rowsPerPage')
            .addEventListener('change', e => {
                this.rowsPerPage = parseInt(e.target.value);
                this.page = 1; // Reset về trang đầu tiên
                this.render();
            });

        // Thêm sự kiện sort
        this.attachSortHandlers();
    }
}
