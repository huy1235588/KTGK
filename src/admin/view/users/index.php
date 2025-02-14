<?php
$title = "Users"; // Tiêu đề của trang
$activeSidebarLink = ['Pages', 'Users', 'Overview']; // Link phụ để hiển thị active
ob_start(); // Bắt đầu lưu nội dung động
?>
<link rel="stylesheet" href="users.css">

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'Users'];
        $pageHeader = 'Users';
        ?>
        <?php include '../../components/page-header.php'; ?>
    </div>

    <!-- Danh sách hoá đơn -->
    <div id="data-grid" class="data-grid-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cột sản phẩm
            const columns = [{
                    key: 'id',
                    label: 'ID',
                    width: '40px',
                    style: 'text-align: center; margin: 10px auto',
                },
                {
                    key: 'userName',
                    label: 'Username',
                    width: '100px',
                },
                {
                    key: 'firstName',
                    label: 'First Name',
                    width: '100px',
                },
                {
                    key: 'lastName',
                    label: 'Last Name',
                    width: '100px',
                },
                {
                    key: 'phone',
                    label: 'Phone',
                    width: '100px',
                },
                {
                    key: 'email',
                    label: 'Email',
                    width: '100px',
                },
                {
                    key: 'address',
                    label: 'Address',
                    width: '100px',
                },
                {
                    key: 'birthday',
                    label: 'Birthday',
                    width: '100px',
                },
                {
                    key: 'role',
                    label: 'Role',
                    width: '100px',
                },
                {
                    key: 'created_At',
                    label: 'Created At',
                    width: '100px',
                },
            ];

            const apiEndpoint = '/api/users.php'; // Đường dẫn API của bạn

            new DataGrid('data-grid', columns, apiEndpoint, {
                rowsPerPage: 5,
                batchSize: 100,
                rowsPerPageOptions: [5, 10, 20, 50, 100],
                orderBy: 'id',
                onRowClick: (rowId) => {
                    // Chuyển hướng đến trang chi tiết sản phẩm
                    window.location.href = `order-details?id=${rowId}`;
                }
            });
        });
    </script>
</article>

<?php
$content = ob_get_clean(); // Lấy nội dung và lưu vào biến $content
include '../../layout.php'; // Nạp layout chính