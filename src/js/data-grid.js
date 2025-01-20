class DataGrid {
    constructor(containerId, columns, apiEndpoint, options = {}) {
        this.container = document.getElementById(containerId);
        this.columns = columns;
        this.apiEndpoint = apiEndpoint;
        this.data = [];
        this.page = 1;
        this.rowsPerPage = options.rowsPerPage || 5;
        this.batchSize = options.batchSize || 25;
        this.totalLoaded = 0; // Số lượng sản phẩm đã tải
        this.totalPage = 0; // Tổng số trang
        this.loading = false;

        this.init();
    }

    async init() {
        await this.loadData(0); // Tải dữ liệu ban đầu
        this.render();
    }

    async loadData(offset) {
        if (this.loading) return; // Ngăn việc gọi API khi đang tải dữ liệu
        this.loading = true;

        try {
            const response = await fetch(
                `${this.apiEndpoint}?limit=${this.batchSize}&offset=${offset}`
            );

            if (!response.ok) {
                throw new Error('Không thể tải dữ liệu');
            }

            const newData = await response.json();
            const products = newData.data;
            const totalPage = Math.ceil(newData.total / this.rowsPerPage);

            this.data = [...this.data, ...products]; // Thêm dữ liệu mới vào danh sách hiện tại
            this.totalLoaded += products.length;
            this.totalPage = totalPage;

        } catch (error) {
            console.error("Lỗi khi tải dữ liệu:", error);
        } finally {
            this.loading = false;
        }
    }

    async goToPage(page) {
        const totalNeeded = page * this.rowsPerPage;
        if (totalNeeded > this.totalLoaded) {
            // Nếu cần thêm dữ liệu
            const offset = this.totalLoaded + 1;
            await this.loadData(offset);
        }

        this.page = page;
        this.render();
    }

    // Hiển thị dữ liệu
    render() {
        this.container.innerHTML = `
            ${this.renderTable()}
            ${this.renderPagination()}
        `;
        this.addEventListeners();
    }

    // Hiển thị tiêu đề cột
    renderTh() {
        return this.columns.map(col => `
            <th
                style="${col.style || ''}${col.width ? `width: ${col.width}` : ''}"
            >
                ${col.label}
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
                        ${col.renderCell(row[col.key], row)}
                    </td>`;
                }
                // Mặc định hiển thị dữ liệu
                return `<td style="${col.width ? `width: ${col.width}` : ''}; ${col.style || ''}">
                    ${row[col.key] || ''}
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
                    ${rows.map(row => `<tr>${this.renderTd(row)}</tr>`).join('')}
                </tbody>
            </table>
        `;
    }

    // Hiển thị phân trang
    renderPagination() {
        const totalPages = this.totalPage;
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
    }
}
