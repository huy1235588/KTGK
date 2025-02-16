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
        $sql = "SELECT *
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

    // Hàm để xoá bạn bè
    public function deleteFriend($userId, $friendId)
    {
        // Viết câu truy vấn
        $sql = "DELETE FROM friends
                WHERE (user_id = ? AND friend_id = ?)
                OR (user_id = ? AND friend_id = ?)
        ";

        // Chuẩn bị
        $stmt = $this->conn->prepare($sql);

        // Bind
        $stmt->bind_param('iiii', $userId, $friendId, $friendId, $userId);

        // Thực thi
        $stmt->execute();

        // Trả về true nếu xoá thành công
        return $stmt->affected_rows > 0;
    }
}
