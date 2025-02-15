<?php
// Tiêu đề của trang
$title = "Products";
// Link phụ để hiển thị active
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Products'];

ob_start();
?>
<link rel="stylesheet" href="products.css">

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products'];
        $pageHeader = 'Products';
        include '../../../components/page-header.php';
        ?>

        <a class="add-user-link" href="add">
            <svg height="17px" width="17px" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill="none" d="M0 0h24v24H0z"></path>
                <path d="M13 8c0-2.21-1.79-4-4-4S5 5.79 5 8s1.79 4 4 4 4-1.79 4-4zm2 2v2h3v3h2v-3h3v-2h-3V7h-2v3h-3zM1 18v2h16v-2c0-2.66-5.33-4-8-4s-8 1.34-8 4z"></path>
            </svg>
            <span>
                Add Product
            </span>
        </a>
    </div>

    <div id="data-grid" class="data-grid-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cột sản phẩm
            const columns = [{
                    key: 'id',
                    label: 'ID',
                    width: '40px',
                    style: 'text-align: center',
                },
                {
                    key: 'headerImage',
                    // label: 'Header Image',
                    // width: '250px',
                    sortAble: false,
                    renderCell: (value) => {
                        return `
                            <div class="header-image-container">
                                <img src="${value}" 
                                    alt="Header Image"
                                    class="header-image"
                                    loading="lazy"
                                 >
                            </div>
                        `;
                    }
                },
                {
                    key: 'title',
                    label: 'Title',
                    width: '100%',
                },
                {
                    key: 'price',
                    label: 'Price',
                    width: '85px',
                    renderCell: (value) => {
                        // Nếu giá là 0 thì hiển thị "Free"
                        if (parseFloat(value) == 0) {
                            return 'Free';
                        }

                        // "13.990" => 13.99
                        return `$${parseFloat(value).toFixed(2)}`;
                    }
                },
                {
                    key: 'discount',
                    label: 'Discount',
                    width: '90px',
                    style: 'text-align: center',
                    renderCell: (value) => {
                        return `${parseInt(value).toFixed(0)}%`;
                    }
                },
                {
                    label: 'Action',
                    width: '100px',
                    sortAble: false,
                    renderCell: (value, row) => {
                        return `
                            <a href="#" class="delete-btn" onclick="deleteUser(event, ${row.id})">Delete</a>
                        `;
                    }
                }
            ];

            const apiEndpoint = '/api/products.php'; // Đường dẫn API của bạn

            new DataGrid('data-grid', columns, apiEndpoint, {
                rowsPerPage: 5,
                batchSize: 100,
                rowsPerPageOptions: [5, 10, 20, 50, 100],
                orderBy: 'id',
                onRowClick: (rowId) => {
                    // Chuyển hướng đến trang chi tiết sản phẩm
                    window.location.href = `product-details?id=${rowId}`;
                }
            });
        });
    </script>
</article>

<?php
// Lấy nội dung và lưu vào biến $content
$content = ob_get_clean();

// Nạp layout chính
include '../../../layout.php';

// Đóng kết nối
DongKetNoi($conn);
