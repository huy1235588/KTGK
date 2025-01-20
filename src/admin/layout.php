<?php
require_once 'middleware/authMiddleware.php';
authMiddleware();

// Khai báo thư viện
require_once 'config/env.php';
require_once __DIR__ . '../../controller/UserController.php';
require_once __DIR__ . '../../controller/ProductController.php';

// Tạo kết nối đến CSDL
$conn = MoKetNoi();

// Tạo đối tượng truy vấn
$userController = new UserController($conn);
$productController = new ProductController($conn);
$products = $productController->getAllProducts();

// Lấy thông tin của người dùng
$user = $userController->getUserById($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title><?php echo $title ?></title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="stylesheet" href="/admin/layout.css">
    <link rel="stylesheet" href="/admin/components/header.css">
    <link rel="stylesheet" href="/admin/components/aside.css">
    <link rel="stylesheet" href="/admin/components/footer.css">
    <link rel="stylesheet" href="/admin/components/page-header.css">
    <link rel="icon" href="/assets/logo.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="/admin/assets/css/copper.css">
    <link rel="stylesheet" href="/css/data-grid.css" />
    <link rel="stylesheet" href="/components/skeleton-loader.css" />
    <link rel="stylesheet" href="/components/tooltip.css" />

    <!-- JQuery -->
    <script src="/admin/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/js/data-grid.js"></script>
</head>

<body class="">
    <!-- START HEADER -->
    <?php
    include 'components/header.php';
    ?>
    <!-- END HEADER -->

    <!-- START SIDEBAR -->
    <script>
        // Set active cho sidebar-link 
        activeSidebarLink = <?php echo json_encode($activeSidebarLink); ?>;
    </script>
    <?php
    include 'components/aside.php';
    ?>
    <!-- END SIDEBAR -->

    <div class="menu-overlay"></div>

    <!-- START MAIN CONTENT -->
    <main id="content" class="main">
        <?= $content ?? ''; ?>
    </main>
    <!-- END MAIN CONTENT -->

    <!-- START FOOTER -->
    <?php
    include 'components/footer.php';
    ?>
    <!-- END FOOTER -->


    <!-- <div id="cropBox"> -->
    <div id="cropBox" style="display: none;">
        <!-- Header -->
        <h2 class="crop-header">
            Choose img
        </h2>

        <!-- Closer button -->
        <button id="cropBtnClose" class="crop-close-btn">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                <path d="m289.94 256 95-95A24 24 0 0 0 351 127l-95 95-95-95a24 24 0 0 0-34 34l95 95-95 95a24 24 0 1 0 34 34l95-95 95 95a24 24 0 0 0 34-34z"></path>
            </svg>
        </button>

        <!-- Img Crop -->
        <div id="imgCropContainer" class="cropBox-img-container">
            <img id="imageToCrop" class="image-to-crop" />
        </div>

        <!-- Confirm button -->
        <div class="crop-btn">
            <button id="cropBtnCancel" type="button" class="crop-cancel-btn">
                Cancel
            </button>
            <button id="cropBtnSave" type="button" class="crop-save-btn">
                Save
            </button>
        </div>
    </div>


    <script>
        var errorHeader;
        var errorDetail;
    </script>


    <?php
    include 'components/error/error-pop.php';

    ?>

    <script src="/admin/assets/js/index.js"></script>

    <script src="/admin/assets/js/cropper.js"></script>
</body>

</html>