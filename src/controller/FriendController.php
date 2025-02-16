<?php

require_once __DIR__ . '../../utils/db_connect.php';

class FriendController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Hàm để lấy toàn bộ bạn bè
    public function getAllFriends($userId)
    {
        // Viết câu truy vấn
        $sql ="SELECT *
            FROM friends f JOIN users u ON f.friend_id = u.id
            WHERE f.user_id = ?
            AND f.status = 'accepted'
        ";

        // Chuẩn bị
        $stmt = $this->conn->prepare($sql);
       
        // Bind
        $stmt->bind_param('i', $userId);

        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // print_r($result->fetch_all(MYSQLI_ASSOC));

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}