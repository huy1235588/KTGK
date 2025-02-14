<?php
$title = "Users"; // Tiêu đề của trang
$activeSidebarLink = ['Pages', 'User Profile', 'Profile']; // Link phụ để hiển thị active
ob_start(); // Bắt đầu lưu nội dung động
?>
<link rel="stylesheet" href="user-profile.css">

<?php

// Nạp file UserController.php
require_once '../../../controller/UserController.php';
require_once '../../../utils/db_connect.php';
require '../../vendor/autoload.php';

// Import Dotenv
use Dotenv\Dotenv;

// Load file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$userId = $_GET['id'];
$conn = MoKetNoi();

// Khởi tạo UserController
$userController = new UserController($conn);

// Lấy thông tin user theo id
$user = $userController->getUserById($userId);


?>
<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'User-profile'];
        $pageHeader = 'Users';
        ?>
        <?php include '../../components/page-header.php'; ?>
    </div>

    <!-- Page content -->
    <div class="page-content">
        <div class="main-content">
            <div class="user-header">
                <img src="/<?php echo $user['avatar']; ?>"
                    class="avatar" alt="User Avatar">
                <h1 id="username">Loading...</h1>
            </div>

            <div class="user-info-grid">
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-id-card"></i> User ID:</span>
                    <span class="info-value" id="userId">
                        <?php echo $user['id']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-user"></i> Full Name:</span>
                    <span class="info-value" id="fullName">
                        <?php echo $user['firstName'] . ' ' . $user['lastName']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-phone"></i> Phone:</span>
                    <span class="info-value" id="phone">
                        <?php echo $user['phone']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                    <span class="info-value" id="email">
                        <?php echo $user['email']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-map-marker-alt"></i> Address:</span>
                    <span class="info-value" id="address">
                        <?php echo $user['address']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-venus-mars"></i> Gender:</span>
                    <span class="info-value" id="gender">
                        <?php echo $user['gender']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-birthday-cake"></i> Birthday:</span>
                    <span class="info-value" id="birthday">
                        <?php echo $user['birthday']; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-user-tag"></i> Role:</span>
                    <span class="info-value">
                        <span id="role" class="badge">
                            <?php echo $user['role']; ?>
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-calendar-plus"></i> Created At:</span>
                    <span class="info-value" id="createdAt">-</span>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Populate user data
        window.onload = function() {
            const user = <?php echo json_encode($user); ?>;

            // Update user info
            document.getElementById('username').textContent = user.username;
            document.getElementById('userId').textContent = user.id;
            document.getElementById('fullName').textContent = `${user.firstName} ${user.lastName}`;
            document.getElementById('phone').textContent = user.phone;
            document.getElementById('email').textContent = user.email;
            document.getElementById('address').textContent = user.address;
            document.getElementById('gender').textContent = user.gender;
            document.getElementById('birthday').textContent = user.birthday;
            document.getElementById('role').textContent = user.role;

            // Role badge styling
            const roleBadge = document.getElementById('role');
            roleBadge.textContent = user.role;
            roleBadge.classList.add(user.role === 'admin' ? 'badge-admin' : 'badge-user');

            // Format created_at date
            const createdAt = new Date(user.created_at);
            document.getElementById('createdAt').textContent =
                `${createdAt.toLocaleDateString()} ${createdAt.toLocaleTimeString()}`;
        };
    </script>

</article>

<?php
$content = ob_get_clean(); // Lấy nội dung và lưu vào biến $content
include '../../layout.php'; // Nạp layout chính