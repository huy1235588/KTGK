<style>
    .menu {
        background-color: rgb(83, 92, 209);
    }

    .menu ul {
        display: flex;
        margin: 0 20px;
    }

    .menu ul li {
        display: flex;
        align-items: center;
        margin-left: 5px;
        color: white;
    }

    .menu ul li a {
        display: inline-block;
        padding: 12px 14px;
        color: white;
    }

    .menu ul li a:hover {
        background-color: #333553;
    }

    .left-menu {
        margin-right: auto;
    }
</style>

<?php
if (!isset($_SESSION)) {
    session_start();
}
$currentUrl = urlencode($_SERVER['REQUEST_URI']);
?>

<nav class="menu">
    <ul>
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
                <p>
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