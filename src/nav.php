<link rel="stylesheet" href="css/nav.css">

<?php
if (!isset($_SESSION)) {
    session_start();
}
$currentUrl = urlencode($_SERVER['REQUEST_URI']);
?>

<nav class="menu">
    <ul class="menu-list">
        <li class="menu-item">
            <a href="trangchu.php">
                Store
            </a>
        </li>
        <li class="menu-item">
            <a href="trangchu.php">
                Product
            </a>
        </li>
        <li class="menu-item">
            <a href="trangchu.php">
                Genre
            </a>
        </li>
        <li class="menu-item">
            <a href="trangchu.php">
                About us
            </a>
        </li>

        <!-- Search -->
        <li class="search">
            <div class="search-container">
                <div class="search-input-container">
                    <input placeholder="Search store" class="search-input" type="text" value="">
                </div>

                <!-- Dropdown -->
                <div class="search-dropdown"></div>

                <button class="button-clear"
                    type="button"
                    tabindex="0" type="button">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z"></path>
                    </svg>
                </button>

                <button class="button-search" type="button">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="searchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                    </svg>
                </button>
            </div>
        </li>

        <?php if (isset($_SESSION['username'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <li class="menu-item">
                    <a href="cart.php">
                        Cart
                    </a>
                </li>
            <?php else: ?>
                <li class="menu-item">
                    <a href="">
                        Website management
                    </a>
                </li>

            <?php endif; ?>
            <li class="menu-item">
                <p class="username">
                    Hello
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </p>
                <a href="dangxuat.php?redirect=<?= $currentUrl ?>">
                    Log out
                </a>
            </li>
        <?php else: ?>
            <li class="menu-item">
                <a href="dangnhap.php?redirect=<?= $currentUrl ?>">
                    Log in
                </a>
            </li>
            <li class="menu-item">
                <a href="dangky.php">
                    Sign up
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    const searchInput = document.querySelector('.search-input');
    const buttonSearch = document.querySelector('.button-search');
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

        // khi nhấn nút search
        buttonSearch.addEventListener('click', () => {
            window.location.href = `search.php?q=${encodeURIComponent(query)}`;
        });

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

    // Ẩn dropdown khi click ra ngoài
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.search-container')) {
            dropdown.style.display = 'none';
        }
    });

    // Hiện lại dropdown khi click vào ô search
    searchInput.addEventListener('click', () => {
        if (searchInput.value.trim().length > 0) {
            dropdown.style.display = 'block';
        }
    });

    // Hover vào dropdown item khi nhấn nút lên xuống
    searchInput.addEventListener('keydown', (event) => {
        const items = dropdown.querySelectorAll('.search-result-item .search-result-link');

        // Không làm gì nếu không có item nào
        if (items.length === 0) {
            return;
        }

        // Tìm index của item đang có class hover
        let index = Array.from(items).findIndex(item => item.classList.contains('hover'));

        // Đặt focus vào item đầu tiên nếu chưa hover
        if (index === -1 && event.key === 'ArrowDown') {
            event.preventDefault();
            items[0].classList.add('hover');
            return;
        }

        // Xử lý sự kiện khi nhấn nút lên xuống
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            items[index].classList.remove('hover');
            index = (index + 1) % items.length;
            items[index].classList.add('hover');
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            items[index].classList.remove('hover');
            index = (index - 1 + items.length) % items.length;
            items[index].classList.add('hover');
        }

        // Chọn sản phẩm khi nhấn enter
        else if (event.key === 'Enter') {
            event.preventDefault();
            if (index === -1) {
                // Chưa hover mục nào -> chuyển hướng đến trang tìm kiếm
                window.location.href = `search.php?q=${encodeURIComponent(searchInput.value)}`;
            } else {
                // Hover mục nào đó -> chuyển hướng tới liên kết của mục đó
                items[index].click();
            }
        }

        // Khi nhấn tab thì focus vào nút search
        else if (event.key === 'Tab') {
            event.preventDefault();
            dropdown.style.display = 'none';
            buttonSearch.focus();
        }
    });

    // Cập nhật index khi hover vào item
    dropdown.addEventListener('mouseover', (event) => {
        const items = dropdown.querySelectorAll('.search-result-item .search-result-link');

        if (!event.target.classList.contains('search-result-link')) {
            return;
        }

        // Loại bỏ class hover khỏi tất cả các item
        items.forEach(item => item.classList.remove('hover'));

        // Thêm class hover vào item được hover
        event.target.classList.add('hover');
    });

    // Chọn sản phẩm từ danh sách
    function selectProduct(productName) {
        searchInput.value = productName;
        dropdown.style.display = 'none';
    }
</script>