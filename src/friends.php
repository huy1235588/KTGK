<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Friend List</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/friends.css">
</head>

<body style="background-color: #f0f2f5; background: none;">
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <?php
    // Nạp file FriendController.php
    require_once 'controller/FriendController.php';
    require_once 'utils/db_connect.php';

    // Kiểm tra xem người dùng đã đăng nhập chưa
    $userId = $_SESSION['user']['id'];
    // Nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
    if (!$userId) {
        echo "<script>
                window.location.href = 'dangnhap.php';
            </script>";
        exit();
    }
    $conn = MoKetNoi();

    // Tạo đối tượng FriendController
    $friendController = new FriendController($conn);

    // Gọi hàm lấy toàn bộ bạn bè
    $friends = $friendController->getAllFriends($userId);

    ?>

    <article class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul class="sidebar-list">
                <li class="sidebar-item">
                    <a href="friends.php" class="active">
                        <span class="title all-friends">
                            Your Friends
                        </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="add-friend.php">
                        <span class="title add-friend">
                            Add Friend
                        </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="sent-requests.php">
                        <span class="title sent-requests">
                            Sent Friend Requests
                        </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="blocked.php">
                        <span class="title blocked">
                            Blocked
                        </span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <h1 class="page-title">
                Your Friends
            </h1>

            <!-- List of friends -->
            <ul class="friend-list">
                <?php if ($result->num_rows > 0) : ?>
                    <?php foreach ($friends as $friend) : ?>
                        <li class="friend">
                            <!-- Avatar -->
                            <img class="friend-avatar"
                                src="/<?php echo $friend['avatar']; ?>"
                                alt="<?php echo $friend['userName']; ?>">

                            <!-- Mame -->
                            <span class="friend-info">
                                <a href="profile.php?id=<?php echo $friend['id']; ?>" class="friend-name">
                                    <?php echo $friend['userName']; ?>
                                </a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No friends found.</p>
                <?php endif; ?>
            </ul>
        </main>
    </article>

    <!-- <script src="script.js"></script> -->

    <?php
    include 'footer.php';
    ?>
</body>

</html>