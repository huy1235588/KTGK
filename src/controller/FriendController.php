<?php

require_once __DIR__ . '../../utils/db_connect.php';

class FriendController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Hàm thực thi câu truy vấn
     * @param string $sql đoạn sql cần thực thi
     * @param array $params mảng chứa các giá trị cần bind vào câu sql
     * @param string $types kiểu dữ liệu của các biến cần bind
     * @return mysqli_stmt kết quả trả về sau khi thực thi
     * @throws Exception lỗi nếu thực thi không thành công
     */
    private function executeQuery(string $sql, string $types = '', array $params = []): mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        return $stmt;
    }

    // Hàm để lấy toàn bộ bạn bè
    public function getAllFriends($userId)
    {
        // Viết câu truy vấn
        $sql = "SELECT *
            FROM friends f JOIN users u ON f.friend_id = u.id
            WHERE f.user_id = ?
            AND f.status = 'accepted'
            UNION
            SELECT *
            FROM friends f JOIN users u ON f.user_id = u.id
            WHERE f.friend_id = ? AND f.status = 'accepted'
        ";

        // Chuẩn bị
        $stmt = $this->executeQuery($sql, 'ii', [$userId, $userId]);

        // Lấy kết quả
        $result = $stmt->get_result();

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
        $stmt = $this->executeQuery($sql, 'i', [$friendId]);

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
        $stmt = $this->executeQuery($sql, 'ii', [$userId, $friendId]);

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
                WHERE (user_id = ? AND friend_id = ?)
                OR (user_id = ? AND friend_id = ?)
                AND status = 'accepted'
        ";

        // Chuẩn bị
        $stmt = $this->executeQuery($sql, 'iiii', [$userId, $friendId, $friendId, $userId]);

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
        $stmt = $this->executeQuery($sql, 'ii', [$userId, $friendId]);

        // Trả về true nếu thêm thành công
        return $stmt->affected_rows > 0;
    }

    // Hàm lấy toàn bộ lời mời kết bạn
    public function getAllFriendRequests($userId)
    {
        // Viết câu truy vấn
        $sql = "SELECT *
                FROM friends f JOIN users u ON f.user_id = u.id
                WHERE f.friend_id = ?
                AND f.status = 'pending'
        ";

        // Chuẩn bị
        $stmt = $this->executeQuery($sql, 'i', [$userId]);

        // Lấy kết quả
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm để chấp nhận lời mời kết bạn
    public function acceptFriendRequest($userId, $friendId)
    {
        $this->conn->begin_transaction();

        try {
            // Cập nhật trạng thái lời mời kết bạn thành chấp nhận
            $sql = "UPDATE friends
                SET status = 'accepted'
                WHERE user_id = ?
                AND friend_id = ?
            ";
            // Thực thi
            $stmt = $this->executeQuery($sql, 'ii', [$friendId, $userId]);

            // Thêm vào bảng friends với trạng thái chấp nhận
            $sql = "INSERT INTO friends (user_id, friend_id, status)
                    VALUES (?, ?, 'accepted')
            ";
            // Thực thi
            $stmt = $this->executeQuery($sql, 'ii', [$userId, $friendId]);

            // Trả về true nếu chấp nhận thành công
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Hàm để từ chối lời mời kết bạn
    public function rejectFriendRequest($userId, $friendId)
    {
        // Viết câu truy vấn
        $sql = "DELETE FROM friends
                WHERE user_id = ?
                AND friend_id = ?
        ";

        // Chuẩn bị
        $stmt = $this->executeQuery($sql, 'ii', [$friendId, $userId]);

        // Trả về true nếu từ chối thành công
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
        $stmt = $this->executeQuery($sql, 'iiii', [$userId, $friendId, $friendId, $userId]);

        // Trả về true nếu xoá thành công
        return $stmt->affected_rows > 0;
    }
}
