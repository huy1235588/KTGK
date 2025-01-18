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
</article>

<?php
$content = ob_get_clean(); // Lấy nội dung và lưu vào biến $content
include '../../layout.php'; // Nạp layout chính