<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Friend</title>
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
    $friends = $friendController->getAllFriendRequests($userId);

    ?>

    <script>
        // Kiểm tra thông báo từ sessionStorage khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            const notification = sessionStorage.getItem('notification');

            if (notification) {
                const {
                    message,
                    type
                } = JSON.parse(notification);
                setNotification(message, type); // Gọi hàm hiển thị thông báo của bạn
                sessionStorage.removeItem('notification'); // Xóa thông báo sau khi hiển thị
            }
        });
    </script>

    <article class="container" id="addFriendContainer" data-user-id="<?php echo $userId; ?>">
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul class="sidebar-list">
                <li class="sidebar-item">
                    <a href="friends.php">
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
                    <a href="sent-requests.php" class="active">
                        <span class="title sent-requests">
                            friend Friend Requests
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
        <main class="content friend-requests">
            <h1 class="page-title">
                Search for friends
            </h1>

            <!-- Container for friend Requests -->
            <div id="friendRequests" class="friend-requests">
                <?php if (count($friends) === 0) : ?>
                    <p class="no-results friend-requests">No friend requests.</p>
                <?php else : ?>
                    <?php foreach ($friends as $friend) : ?>
                        <div class="friend-request-item" data-friend-id="<?php echo $friend['id']; ?>">
                            <!-- Avatar -->
                            <div class="friend-request-avatar">
                                <img src="<?php echo $friend['avatar']; ?>" alt="Avatar">
                            </div>

                            <!-- Info -->
                            <span class="friend-request-info">
                                <?php echo $friend['userName']; ?>
                            </span>

                            <!-- Button -->
                            <div class="friend-request-button">
                                <button class="accept-request" data-friend-id="<?php echo $friend['id']; ?>">
                                    Accept
                                </button>
                                <button class="cancel-request" data-friend-id="<?php echo $friend['id']; ?>">
                                    Cancel Request
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </article>

    <!-- <script src="script.js"></script> -->
    <script>
        document.getElementById('friendRequests').addEventListener('click', async (e) => {
            const target = e.target;

            // Kiểm tra click vào nút Accept hoặc Cancel
            if (target.classList.contains('accept-request') || target.classList.contains('cancel-request')) {
                e.preventDefault();
                const friendId = target.dataset.friendId;
                const action = target.classList.contains('accept-request') ? 'accept' : 'cancel';

                try {
                    // Thêm class loading để hiển thị loading
                    const button = target;
                    const item = button.closest('.friend-request-item');
                    item.classList.add('loading');

                    // Gửi request đến API
                    const response = await fetch(`/api/friend_request.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            userId: <?php echo $userId; ?>,
                            friendId: friendId,
                            action: action
                        })
                    });

                    if (!response.ok) throw new Error('Request failed');

                    // Xoá class loading
                    item.classList.remove('loading');

                    // Xóa element nếu thành công
                    if (item) {
                        item.remove();

                        // Hiển thị thông báo nếu không còn request nào
                        const container = document.getElementById('friendRequests');
                        if (container.children.length === 0) {
                            container.innerHTML = `<p class="no-results friend-requests">No friend requests.</p>`;
                        }
                    }

                } catch (error) {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại');
                }
            }
        });
    </script>

    <?php
    include 'footer.php';
    ?>
</body>

</html>