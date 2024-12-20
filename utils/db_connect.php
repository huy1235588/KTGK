<?php
function MoKetNoi()
{

    // Thông tin cấu hình cơ sở dữ liệu
    $host = "localhost"; // Tên server, mặc định là localhost
    $username = "root";  // Tên đăng nhập, mặc định là root
    $password = "";      // Mật khẩu, để trống nếu dùng XAMPP hoặc LAMP
    $database = "baithigiuaky"; // Tên cơ sở dữ liệu

    // Mở kết nối đến cơ sở dữ liệu
    $conn = new mysqli($host, $username, $password, $database);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    return $conn;
}

function DongKetNoi($conn)
{
    if ($conn) {
        $conn->close();
    }
}
