<?php
// Tiêu đề của trang
$title = 'Product Details';
// Nhúng file header
$activeSidebarLink = ['Pages', 'E-commerce', 'Orders', 'Order Details'];

ob_start();
?>
<link rel="stylesheet" href="order-details.css">

<?php
// Lấy ID hoá đơn từ URL
$orderId = $_GET['id'];
?>

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products', 'Product Details'];
        $pageHeader = 'Product Details';
        include '../../../../components/page-header.php';
        ?>

        <!-- Điều hướng sản phẩm tiếp theo hoặc trước đó -->
        <div class="product-navigation">
            <a href="/admin/view/ecommerce/products/product-details/?id=<?php echo $productId - 1 ?>"
                class="product-navigation-link"
                data-tooltip="Previous Product"
                data-placement="top">

                <!-- Icon -->
                <img src="/admin/assets/icon/arrow1-left.svg" alt="Previous Product">
            </a>
            <a href="/admin/view/ecommerce/products/product-details/?id=<?php echo $productId + 1 ?>"
                class="product-navigation-link"
                data-tooltip="Next Product"
                data-placement="top">

                <!-- Icon -->
                <img src="/admin/assets/icon/arrow1-right.svg" alt="Next Product">
            </a>
        </div>
    </div>

    <div id="data-grid" class="data-grid-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cột sản phẩm
            const columns = [{
                    key: 'product_id',
                    label: 'Product ID',
                    width: '40px',
                    style: 'text-align: center;',
                },
                {
                    key: 'headerImage',
                    label: 'Image',
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
                }
            ];

            const apiEndpoint = '/api/order_detail.php?id=<?php echo $orderId ?>'; // Đường dẫn API của bạn

            new DataGrid('data-grid', columns, apiEndpoint, {
                rowsPerPage: 5,
                batchSize: 100,
                rowsPerPageOptions: [5, 10, 20, 50, 100],
                orderBy: 'id',
                onRowClick: (rowId) => {
                    // Chuyển hướng đến trang chi tiết sản phẩm
                    window.location.href = `/admin/view/ecommerce/products/product-details?id=${rowId}`;
                }
            });
        });
    </script>

</article>

<?php
$content = ob_get_clean();
include '../../../../layout.php';

DongKetNoi($conn);
?>