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
                    <a href="friends.php">
                        <span class="title all-friends">
                            Your Friends
                        </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="add-friend.php" class="active">
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
        <main class="content add-friend">
            <h1 class="page-title">
                Search for friends
            </h1>

            <!-- Search Input Field -->
            <div class="friend-search-container">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="friendSearchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                </svg>
                <input type="text" id="friendSearch" placeholder="Enter friend's name...">
            </div>
            <!-- Container for Search Results -->
            <div id="searchResults" class="search-results"></div>
        </main>
    </article>

    <!-- <script src="script.js"></script> -->
    <script>
        // Cache DOM elements
        const friendSearchInput = document.getElementById('friendSearch');
        const resultsContainer = document.getElementById('searchResults');
        const userId = document.body.dataset.userId; // Get userId from data attribute

        // Hàm debounce để giảm số lần gửi request khi người dùng nhập nhanh
        const debounce = (func, delay = 500) => {
            let timeoutId;
            return (...args) => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        };

        // Event listeners
        friendSearchInput.addEventListener('input', debounce(searchFriends));

        // Hàm gửi request tìm kiếm bạn bè
        async function searchFriends() {
            const query = friendSearchInput.value.trim();

            // Nếu query rỗng thì xóa kết quả hiển thị
            if (!query) {
                resultsContainer.innerHTML = '';
                return;
            }

            try {
                // Hiển thị loading indicator
                showLoadingIndicator();

                // Gửi request tìm kiếm bạn bè
                const response = await fetch(`api/search_friend.php?userName=${encodeURIComponent(query)}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                displayResults(data);

            } catch (error) {
                console.error('Search error:', error);
                showErrorMessage('Error retrieving search results.');
            }
        }

        // Hiển thị kết quả tìm kiếm
        function displayResults(data) {
            resultsContainer.innerHTML = '';

            // Nếu có kết quả thì hiển thị danh sách bạn bè
            if (data.success && data.friends?.length > 0) {
                const fragment = document.createDocumentFragment();

                data.friends.forEach(friend => {
                    const friendItem = createFriendElement(friend);
                    fragment.appendChild(friendItem);
                });

                // Thêm danh sách bạn bè vào container
                resultsContainer.appendChild(fragment);
            } else {
                showNoResultsMessage();
            }
        }

        // Tạo element hiển thị thông tin bạn bè
        function createFriendElement(friend) {
            const div = document.createElement('div');
            div.className = 'add-friend-item';

            const img = document.createElement('img');
            img.className = 'add-friend-avatar';
            img.src = `/${friend.avatar}`;
            img.alt = 'Avatar';

            const span = document.createElement('span');
            span.className = 'add-friend-info';
            span.textContent = friend.userName;

            const button = document.createElement('button');
            button.textContent = 'Add Friend';
            button.addEventListener('click', () => sendFriendRequest(friend.id));

            div.append(img, span, button);
            return div;
        }

        // Gửi request kết bạn
        async function sendFriendRequest(friendId) {
            try {
                const response = await fetch(
                    `api/send_friend_request.php?userId=${userId}&friendId=${friendId}`
                );

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                handleRequestResponse(data);

            } catch (error) {
                console.error('Request error:', error);
                handleRequestResponse({
                    success: false
                });
            }
        }

        // Xử lý kết quả request
        function handleRequestResponse(data) {
            const notification = {
                message: data.success ?
                    'Friend request sent!' : 'Failed to send friend request. Please try again later.',
                type: data.success ? 'success' : 'error'
            };

            sessionStorage.setItem('notification', JSON.stringify(notification));
            window.location.reload();
        }

        // UI helpers
        function showLoadingIndicator() {
            resultsContainer.innerHTML = '<div class="loading">Searching...</div>';
        }

        function showNoResultsMessage() {
            resultsContainer.innerHTML = '<p class="no-results">No friends found.</p>';
        }

        function showErrorMessage(message) {
            resultsContainer.innerHTML = `<p class="error">${message}</p>`;
        }
    </script>

    <?php
    include 'footer.php';
    ?>
</body>

</html>