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
    </div>

    <div id="data-grid" class="data-grid-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cột sản phẩm
            const columns = [{
                    key: 'id',
                    label: 'ID',
                    width: '50px',
                    style: 'text-align: center',
                },
                {
                    key: 'headerImage',
                    label: 'Header Image',
                    width: '250px',
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
                    width: '100px',
                    renderCell: (value) => {
                        // "13.990" => 13.99
                        return `$${parseFloat(value).toFixed(2)}`;
                    }
                },
                {
                    key: 'discount',
                    label: 'Discount',
                    width: '100px',
                    style: 'text-align: center',
                    renderCell: (value) => {
                        return `${parseInt(value).toFixed(0)}%`;
                    }
                }
            ];

            const apiEndpoint = '/api/products.php'; // Đường dẫn API của bạn

            new DataGrid('data-grid', columns, apiEndpoint, {
                rowsPerPage: 5,
                batchSizes: [5, 10, 20],
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
