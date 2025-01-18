<?php
include 'middleware/authMiddleware.php';
authMiddleware();

// Khai báo thư viện
require_once 'config/env.php';
require_once 'config/db.php';
require_once 'controller/UserController.php';

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
    <link rel="icon" href="../assets/logo.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="components/header.css">
    <link rel="stylesheet" href="components/aside.css">
    <link rel="stylesheet" href="components/footer.css">
    <link rel="stylesheet" href="components/page-header.css">
    <link rel="stylesheet" href="admin.css">

    <!-- JQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
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
        activeSidebarLink = ["Dashboards", "Default"];
    </script>
    <?php
    include 'components/aside.php';
    ?>
    <!-- END SIDEBAR -->

    <div class="menu-overlay"></div>

    <!-- START MAIN CONTENT -->
    <main id="content" class="main">
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
    </main>
    <!-- END MAIN CONTENT -->

    <!-- START FOOTER -->
    <?php
    include 'components/footer.php';
    ?>
    <!-- END FOOTER -->

    <script src="assets/js/index.js"></script>
</body>

</html>