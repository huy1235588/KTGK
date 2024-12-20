<?php
function TaoDatabaseVaTable()
{
    include_once 'db_connect.php';
    $conn = MoKetNoi();

    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    // SQL tạo bảng users nếu chưa tồn tại
    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        address VARCHAR(255),
        gender ENUM('Nam', 'Nữ') NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sqlUsers) === TRUE) {
        echo "Bảng 'users' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'users': " . $conn->error);
    }

    // SQL tạo bảng products nếu chưa tồn tại
    $sqlProducts = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10, 3),
        original_price DECIMAL(10, 3) NOT NULL,
        description TEXT,
        image VARCHAR(255) NOT NULL
    )";

    if ($conn->query($sqlProducts) === TRUE) {
        echo "Bảng 'products' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'products': " . $conn->error);
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

function InsertSanPhamMau()
{
    include_once 'db_connect.php';
    $conn = MoKetNoi();

    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO products (name, price, original_price, description, image) VALUES 
    ('Táo Gala nhập khẩu Trung Quốc 1kg (trái từ 140g trở lên)', NULL, 53.400, '', 'images/products/tao/tao_gala_trung_quoc'),
    ('Táo Ninh Thuận 500g', 12.000, 17.000, '', 'images/products/tao/tao_ninh_thuan'),
    ('Táo Fuji', 49.000, 72.000, '', 'images/products/tao/tao_fuji'),
    ('Táo Gala Pháp thùng 2.5kg', 145.000, 172.500, '', 'images/products/tao/tao_gala_phap'),
    ('Táo Ambrosia nhập khẩu Mỹ 1kg', 105.000, 115.000, '', 'images/products/tao/tao_ambrosia_my'),
    ('Táo Rockit nhập khẩu New Zealand ống 3 trái', NULL, 94.000, '', 'images/products/tao/tao_rockit_new_zealand'),
    ('Táo Autumn Glory Mỹ', NULL, 79.000, '', 'images/products/tao/tao_autumn_glory_my'),
    ('Táo Pink Lady nhập khẩu New Zealand 1kg', NULL, 85.000, '', 'images/products/tao/tao_pink_lady_new_zealand'),
    
    ('Giỏ quà trái cây Ấm áp', 149.000, 187.000, '', 'images/products/qua/gio_qua_am_ap'),
    ('Hộp quà trái cây Tươi Khoẻ', 490.000, 519.000, '', 'images/products/qua/hop_qua_tuoi_khoe'),
    ('Giỏ quà trái cây Lộc đầy', 279.000, 328.000, '', 'images/products/qua/gio_qua_loc_day'),
    ('Hộp quà trái cây Trọn Vẹn', 459.000, 600.000, '', 'images/products/qua/hop_qua_tron_ven'),
    ('Hộp quà trái cây Thành Công', 469.000, 495.000, '', 'images/products/qua/hop_qua_thanh_cong'),
    ('Hộp quà trái cây Tri Ân', 545.000, 575.000, '', 'images/products/qua/hop_qua_tri_an'),
    ('Hộp quà trái cây Hạnh Phúc', 490.000, 519.000, '', 'images/products/qua/hop_qua_hanh_phuc'),
    ('Hộp quà trái cây Yêu Thương', 490.000, 519.000, '', 'images/products/qua/hop_qua_yeu_thuong'),
    ('Giỏ quà trái cây Sống khoẻ', 299.000, 373.000, '', 'images/products/qua/gio_qua_song_khoe'),

    ('Trái cây sấy Rộp Rộp gói 100g', 26.000, NULL, '', 'images/products/say/trai_cay_say_rop_rop'),
    ('Mít sấy Nhabexims gói 100g', 32.000, NULL, '', 'images/products/say/mit_say_nhabexims'),
    ('Xoài sấy dẻo Đỉnh Nam Wenatur gói 100g', 39.000, NULL, '', 'images/products/say/xoai_say_deo_dinh_nam_wenatur'),
    ('Khoai môn sấy Rộp Rộp gói 100g', 36.000, NULL, '', 'images/products/say/khoai_mon_say_rop_rop'),
    ('Dâu sấy dẻo Ohla gói 30g', 19.000, NULL, '', 'images/products/say/dau_say_deo_ohla'),
    ('Xoài chín cây sấy dẻo Ohla gói 35g', 19.000, NULL, '', 'images/products/say/xoai_chin_cay_say_deo_ohla'),
    ('Trái cây sấy giòn Đỉnh Nam Wenatur gói 100g', 28.500, NULL, '', 'images/products/say/trai_cay_say_gion_dinh_nam_wenatur'),
    ('Chuối sấy giòn kẹp me cay Tamarind House gói 90g', 59.000, 85.000, '', 'images/products/say/chuoi_say_gion_kep_me_cay_tamarind_house')
    ";

    if ($conn->query($sql) === TRUE) {
        echo "Sản phẩm '$name' đã được thêm thành công.<br>";
    } else {
        echo "Lỗi khi thêm sản phẩm '$name': " . $conn->error . "<br>";
    }

    DongKetNoi($conn);
}

// Gọi hàm tạo database và table
TaoDatabaseVaTable();

// Gọi hàm chèn sản phẩm mẫu
InsertSanPhamMau();
