<?php
$title = "Trang chủ"; // Tiêu đề của trang
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Products']; // Link phụ để hiển thị active
ob_start(); // Bắt đầu lưu nội dung động
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

</article>

<?php
$content = ob_get_clean(); // Lấy nội dung và lưu vào biến $content
include '../../../layout.php'; // Nạp layout chính