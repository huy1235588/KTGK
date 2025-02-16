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
                        <li class="friend" id="<?php echo $friend['id']; ?>">
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


                            <!-- Delete Button -->
                            <button class="delete-btn"
                                onclick="showDeleteModal(<?php echo $friend['id']; ?>, '<?php echo addslashes(htmlspecialchars($friend['userName'])); ?>')">
                                ✕
                            </button>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No friends found.</p>
                <?php endif; ?>
            </ul>
        </main>
    </article>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete <span id="friendName"></span>?</p>
            <div class="modal-buttons">
                <button onclick="hideDeleteModal()">Cancel</button>
                <a id="confirmDelete" href="#" class="confirm-delete-btn">Delete</a>
            </div>
        </div>
    </div>

    <!-- <script src="script.js"></script> -->
    <script>
        function showDeleteModal(friendId, friendName) {
            const confirmDelete = document.getElementById('confirmDelete');
            document.getElementById('friendName').textContent = friendName;
            document.getElementById('deleteModal').style.display = 'block';


            confirmDelete.addEventListener('click', function() {
                fetch(`/api/delete_friend.php?userId=<?php echo $userId; ?>&friendId=${friendId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            sessionStorage.setItem('notification', JSON.stringify({
                                message: 'Friend deleted successfully!',
                                type: 'success'
                            }));
                            window.location.reload();
                        } else {
                            sessionStorage.setItem('notification', JSON.stringify({
                                message: 'Failed to delete friend. Please try again later.',
                                type: 'error'
                            }));
                        }
                    });
            });
        }

        // Ẩn modal
        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Đóng modal khi click ra ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                hideDeleteModal();
            }
        }
    </script>

    <?php
    include 'footer.php';
    ?>
</body>

</html>