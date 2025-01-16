<style>
    .sidebar-menu {
        position: sticky;
        top: 20px;
        width: 200px;
        max-height: calc(100vh - 50px);
        /* Chiều cao tối đa của menu */
        background-color: #f8f9fa;
        padding: 10px;
        padding-right: 0;
        margin: 0 10px 30px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        /* Ẩn nội dung tràn ra ngoài */
        transition: max-height 0.3s ease;
        scrollbar-gutter: stable;
    }

    .sidebar-menu:hover {
        overflow-y: auto;
        /* Hiển thị thanh cuộn dọc */
    }

    .sidebar-menu ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 10px;
    }

    .sidebar-menu a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
        display: block;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .sidebar-menu a:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>

<?php
$menu_items = [
    "TRÁI CÂY TƯƠI",
    "TRÁI CÂY NHẬP KHẨU",
    "TRÁI CÂY SẤY KHÔ",
    "NƯỚC ÉP TRÁI CÂY",
    "GIỎ QUÀ TRÁI CÂY",
    "DỊCH VỤ GIAO HÀNG",
    "ƯU ĐÃI ĐẶC BIỆT",
    "KHUYẾN MÃI HÔM NAY",
    "BÀI VIẾT VỀ SỨC KHỎE",
    "LIÊN HỆ VÀ ĐẶT HÀNG"
];
?>

<aside class="sidebar-menu">
    <ul>
        <?php foreach ($menu_items as $item): ?>
            <li>
                <a href="#"><?php echo $item; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>