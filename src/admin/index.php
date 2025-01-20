<?php
// Tiêu đề của trang
$title = "Trang chủ";
// Link phụ để hiển thị active
$activeSidebarLink = ['Dashboards', 'Default'];

ob_start();
?>
<link rel="stylesheet" href="admin.css">

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Dashboard', 'Default'];
        $pageHeader = 'Dashboard';
        include 'components/page-header.php';
        ?>

        <a class="add-user-link" href="view/ecommerce/users/add-user">
            <svg height="17px" width="17px" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill="none" d="M0 0h24v24H0z"></path>
                <path d="M13 8c0-2.21-1.79-4-4-4S5 5.79 5 8s1.79 4 4 4 4-1.79 4-4zm2 2v2h3v3h2v-3h3v-2h-3V7h-2v3h-3zM1 18v2h16v-2c0-2.66-5.33-4-8-4s-8 1.34-8 4z"></path>
            </svg>
            <span>
                Add users
            </span>
        </a>
    </div>

</article>

<?php
// Lấy nội dung và lưu vào biến $content
$content = ob_get_clean();

// Nạp layout chính
include 'layout.php';

// Đóng kết nối
DongKetNoi($conn);
