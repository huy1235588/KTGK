<?php
// Tiêu đề của trang
$title = "Add Product";
// Link phụ để hiển thị active
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Add Product'];

ob_start();
?>
<link rel="stylesheet" href="product-add.css">

<?php
// Nạp file ProductController.php
require_once '../../../../../controller/ProductController.php';
require_once '../../../../../utils/db_connect.php';

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo ProductController
$productController = new ProductController($conn);

// Lấy tất cả thể loại
$types = $productController->getTypes();

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $txtTitle = htmlspecialchars($_POST['title']);
    $txtType = htmlspecialchars($_POST['type']);

    echo $txtTitle;
    echo $txtType;
}
?>

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products', 'Add Product'];
        $pageHeader = 'Add Product';
        include '../../../../components/page-header.php';
        ?>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title" class="form-label">
                Title
            </label>
            <div class="form-control-wrapper">
                <input class="form-control"
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter title"
                    value="<?php echo $txtTitle ?? ''; ?>">

                <fieldset class="form-control-outline"></fieldset>
            </div>

            <?php
            if (isset($errors['title'])) {
                echo '<span class="error-message">' . $errors['title'] . '</span>';
            }
            ?>
        </div>

        <div class="form-group">
            <label for="type" class="form-label">
                Type
            </label>
            <div class="form-control-wrapper select">
                <div tabindex="0"
                    role="combobox"
                    id="type"
                    class="select-box">
                    <div class="select-value">
                        <em style="color: gray;">Select Type</em>
                    </div>
                </div>
                <input class="form-control-select"
                    id="type"
                    name="type"
                    value="<?php echo $txtType ?? ''; ?>">

                <svg class="select-icon"
                    focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                    <path d="M7 10l5 5 5-5z"></path>
                </svg>
                <fieldset class="form-control-outline"></fieldset>
            </div>

            <?php
            if (isset($errors['price'])) {
                echo '<span class="error-message">' . $errors['price'] . '</span>';
            }
            ?>

            <div class="dropdown-select-menu">
                <ul class="dropdown-select-list">
                    <?php foreach ($types as $type) : ?>
                        <li class="dropdown-select-item">
                            <span class="dropdown-select-item-checkbox-wrapper">
                                <input
                                    class="dropdown-select-item-checkbox"
                                    data-indeterminate="false"
                                    type="checkbox">

                                <svg class="dropdown-select-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                                    <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                </svg>
                            </span>

                            <div class="dropdown-select-item-text">
                                <span class="dropdown-select-item-text-primary">
                                    <?php echo $type['name']; ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Submit -->
        <div class="form-group form-group-submit">
            <button type="submit" class="submit-btn">Add Product</button>
        </div>
    </form>
</article>

<script src="product-add.js"></script>

<?php
// Lấy nội dung và lưu vào biến $content
$content = ob_get_clean();

// Nạp layout chính
include '../../../../layout.php';

// Đóng kết nối
DongKetNoi($conn);
