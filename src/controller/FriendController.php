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

    // Hàm để lấy bạn bè theo friendId
    public function getFriendByFriendId($friendId)
    {
        // Viết câu truy vấn
        $sql = "SELECT *
            FROM friends f JOIN users u ON f.user_id = u.id
            WHERE f.friend_id = ?
        ";

        // Chuẩn bị
        $stmt = $this->conn->prepare($sql);

        // Bind
        $stmt->bind_param('i', $friendId);

        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm xem đã gửi lời mời kết bạn chưa
    public function isFriendRequestSent($userId, $friendId)
    {
        // Viết câu truy vấn
        $sql = "SELECT * FROM friends
                WHERE user_id = ?
                AND friend_id = ?
                AND status = 'pending'
        ";

        // Chuẩn bị
        $stmt = $this->conn->prepare($sql);

        // Bind
        $stmt->bind_param('ii', $userId, $friendId);

        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Trả về true nếu đã gửi lời mời kết bạn
        return $result->num_rows > 0;
    }

    // Hàm để kiểm tra đã là bạn bè chưa
    public function isFriend($userId, $friendId)
    {
        // Viết câu truy vấn
        $sql = "SELECT * FROM friends
                WHERE user_id = ? 
                AND friend_id = ?
                AND status = 'accepted'
        ";

        // Chuẩn bị
        $stmt = $this->conn->prepare($sql);

        // Bind
        $stmt->bind_param('ii', $userId, $friendId);
        
        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Trả về true nếu đã là bạn bè
        return $result->num_rows > 0;
    }

    // Hàm để gửi lời mời kết bạn
    public function sendFriendRequest($userId, $friendId)
    {
        // Viết câu truy vấn
        $sql = "INSERT INTO friends (user_id, friend_id, status)
                VALUES (?, ?, 'pending')
        ";

        // Chuẩn bị
        $stmt = $this->conn->prepare($sql);

        // Bind
        $stmt->bind_param('ii', $userId, $friendId);

        // Thực thi
        $stmt->execute();

        // Trả về true nếu thêm thành công
        return $stmt->affected_rows > 0;
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
