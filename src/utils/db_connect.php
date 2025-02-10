<?php
function MoKetNoi()
{
    $host = "localhost:3307";
    $username = "root";
    $password = "";
    $database = "baithigiuaky";

    // Kết nối tới MySQL server (không chỉ định database)
    $conn = new mysqli($host, $username, $password);

    // Cấu hình kết nối
    $conn->set_charset("utf8mb4");

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Tạo cơ sở dữ liệu nếu chưa tồn tại
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if (!$conn->query($sql)) {
        die("Không thể tạo cơ sở dữ liệu: " . $conn->error);
    }

    // Kết nối tới cơ sở dữ liệu
    $conn->select_db($database);

    return $conn;
}


function DongKetNoi($conn)
{
    if ($conn) {
        $conn->close();
        $conn = null;
    }
}
