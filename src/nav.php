<link rel="stylesheet" href="css/nav.css">

<?php
if (!isset($_SESSION)) {
    session_start();
}
$currentUrl = urlencode($_SERVER['REQUEST_URI']);
?>

<nav class="menu">
    <ul class="menu-list">
        <li>
            <a href="trangchu.php">
                Trang chủ
            </a>
        </li>
        <li>
            <a href="trangchu.php">
                Sản phẩm
            </a>
        </li>
        <li>
            <a href="trangchu.php">
                Danh mục
            </a>
        </li>
        <li class="left-menu">
            <a href="trangchu.php">
                Giới thiệu
            </a>
        </li>

        <!-- Search -->
        <li class="search">
            <form action="timkiem.php" method="get" class="search-form">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="searchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                </svg>

                <div class="search-input-container">
                    <input placeholder="Search store" class="search-input" type="text" value="">
                    <!-- Dropdown -->
                    <div class="search-dropdown"></div>
                </div>

                <button class="button-clear"
                    tabindex="0" type="button">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z"></path>
                    </svg>
                </button>
            </form>
        </li>

        <?php if (isset($_SESSION['username'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <li>
                    <a href="cart.php">
                        Giỏ hàng
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="">
                        Quản trị website
                    </a>
                </li>

            <?php endif; ?>
            <li>
                <p class="username">
                    Xin chào
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </p>
                <a href="dangxuat.php?redirect=<?= $currentUrl ?>">
                    đăng xuất
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="dangnhap.php?redirect=<?= $currentUrl ?>">
                    Đăng nhập
                </a>
            </li>
            <li>
                <a href="dangky.php">
                    Đăng ký
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    const searchInput = document.querySelector('.search-input');
    const buttonClear = document.querySelector('.button-clear');
    const dropdown = document.querySelector('.search-dropdown');

    // Xoá nội dung trong ô search
    buttonClear.addEventListener('click', () => {
        searchInput.value = '';
        dropdown.style.display = 'none';
    });

    // Hiển thị gợi ý sản phẩm
    searchInput.addEventListener('input', async () => {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            buttonClear.style.opacity = 1;

            // Gọi API để lấy danh sách sản phẩm
            const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);

            // Chuyển đổi response sang text (HTML)
            const html = await response.text();

            // Hiển thị danh sách sản phẩm
            dropdown.innerHTML = html;

            // Hiển thị dropdown
            dropdown.style.display = 'block';
        } else {
            buttonClear.style.opacity = 0;
            dropdown.style.display = 'none';
        }
    });

    // Chọn sản phẩm từ danh sách
    function selectProduct(productName) {
        searchInput.value = productName;
        dropdown.style.display = 'none';
    }
</script>