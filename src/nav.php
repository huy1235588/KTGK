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
            <a href="index.php">
                Store
            </a>
        </li>
        <li class="menu-item">
            <a href="index.php">
                Product
            </a>
        </li>
        <li class="menu-item">
            <a href="index.php">
                Genre
            </a>
        </li>
        <li class="menu-item">
            <a href="index.php">
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

        <?php if (isset($_SESSION['user']['username'])): ?>
            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'user'): ?>
                <li class="menu-item">
                    <a href="cart.php" class="cart-link">
                        Cart
                        <span class="cart-quantity"> </span>

                        <script>
                            // Lấy số lượng sản phẩm trong giỏ hàng
                            const cartQuantity = document.querySelector('.cart-quantity');
                            const cartLink = document.querySelector('.cart-link');

                            // Hiển thị số lượng sản phẩm trong giỏ hàng
                            cartQuantity.textContent = <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>;

                            // Cập nhật hiển thị số lượng sản phẩm
                            function updateDisplay() {
                                if (cartQuantity.textContent === '0') {
                                    cartQuantity.style.display = 'none';
                                } else {
                                    cartQuantity.style.display = 'block';
                                }
                            }

                            // Cập nhật hiển thị số lượng sản phẩm
                            updateDisplay();

                            // Cập nhật số lượng sản phẩm khi giỏ hàng thay đổi
                            const observer = new MutationObserver(() => {
                                updateDisplay();
                            });

                            // Theo dõi sự thay đổi của nút giỏ hàng
                            observer.observe(cartQuantity, {
                                childList: true,
                                characterData: true,
                                subtree: true
                            });
                        </script>
                    </a>
                </li>
            <?php else: ?>
                <li class="menu-item">
                    <a href="/admin">
                        Website management
                    </a>
                </li>

            <?php endif; ?>
            <li class="menu-item account">
                <div class="username">
                    Hello
                    <?= htmlspecialchars($_SESSION['user']['username']) ?>
                </div>
                <div class="account-dropdown hs-hidden">
                    <ul class="account-dropdown-list">
                        <h6 class="account-dropdown-title">
                            My Information
                        </h6>

                        <li class="account-dropdown-item">
                            <a href="profile.php?id=<?= $_SESSION['user']['username'] ?>">
                                <div class="icon">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" font-size="20px" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 2H8a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-6 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM19 15H9v-.25C9 12.901 11.254 11 14 11s5 1.901 5 3.75V15z"></path>
                                        <path d="M4 8H2v12c0 1.103.897 2 2 2h12v-2H4V8z"></path>
                                    </svg>
                                </div>
                                Information Management
                            </a>
                        </li>

                        <hr class="account-dropdown-divider">

                        <li class="account-dropdown-item">
                            <a href="dangxuat.php?redirect=<?= $currentUrl ?>">
                                <div class="icon">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" font-size="20px" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path>
                                        <path d="M20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4H9v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z"></path>
                                    </svg>
                                </div>
                                Log out
                            </a>
                        </li>
                    </ul>
                </div>
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

<script src="js/nav.js"></script>