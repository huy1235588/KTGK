<?php
include '../../../middleware/authMiddleware.php';
authMiddleware();

// Khai báo thư viện
require_once '../../../config/env.php';
require_once '../../../config/db.php';
require_once '../../../controller/UserController.php';

$conn = MoKetNoi();

// Tạo đối tượng truy vấn
$userController = new UserController($conn);

// Lấy thông tin của người dùng
$user = $userController->getUserById($_SESSION['username']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Dashboard | Front - Admin &amp; Dashboard Template</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="/assets/logo.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="/admin/components/header.css">
    <link rel="stylesheet" href="/admin/components/aside.css">
    <link rel="stylesheet" href="/admin/components/footer.css">
    <link rel="stylesheet" href="/admin/components/page-header.css">
    <link rel="stylesheet" href="./products.css">

    <!-- JQuery -->
    <script src="../../../assets/js/jquery-3.7.1.min.js"></script>
</head>

<body class="">
    <!-- START HEADER -->
    <?php
    include '../../../components/header.php';
    ?>
    <!-- END HEADER -->

    <!-- START SIDEBAR -->
    <script>
        // Set active cho sidebar-link 
        activeSidebarLink = ["Pages", "E-commerce", "Products", "Products"];
    </script>
    <?php
    include '../../../components/aside.php';
    ?>
    <!-- END SIDEBAR -->

    <div class="menu-overlay"></div>

    <!-- START MAIN CONTENT -->
    <main id="content" class="main">
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
    </main>
    <!-- END MAIN CONTENT -->

    <!-- START FOOTER -->
    <?php
    include '../../../components/footer.php';
    ?>
    <!-- END FOOTER -->

    <script src="/admin/assets/js/index.js"></script>
</body>

</html>