<?php
// Tiêu đề của trang
$title = "Add Product";
// Link phụ để hiển thị active
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Add Product'];

ob_start();
?>
<link rel="stylesheet" href="admin.css">

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products', 'Add Product'];
        $pageHeader = 'Add Product';
        include '../../../../components/page-header.php';
        ?>
    </div>

    <form action="save-add" method="post" enctype="multipart/form-data">
        <div class="form-group
            <?php echo isset($errors['title']) ? ' invalid' : ''; ?>">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo $title; ?>">
            <?php
            if (isset($errors['title'])) {
                echo '<span class="error-message">' . $errors['title'] . '</span>';
            }
            ?>

            <!-- TODO -->
            <!-- 
            
            Thêm input cho các trường còn lại     
            
             -->
        </div>
    </form>

</article>

<?php
// Lấy nội dung và lưu vào biến $content
$content = ob_get_clean();

// Nạp layout chính
include '../../../../layout.php';

// Đóng kết nối
DongKetNoi($conn);
