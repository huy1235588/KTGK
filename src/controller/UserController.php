<?php

require_once __DIR__ . '../../utils/db_connect.php';

class UserController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Hàm để lấy toàn bộ user
    public function getAllUsers()
    {
        $users = $this->conn->query("SELECT * FROM users");

        return $users->fetchAll();
    }

    // Hàm để lấy user theo id
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm để lấy user theo username
    public function getUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("i", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm để lấy toàn bộ user
    public function getUserByPage($offset, $limit, $columns, $query, $sort, $order)
    {
        $sql = "SELECT * FROM users";

        // Nếu có query
        if ($query) {
            $sql .= " WHERE Username LIKE '%$query%' 
                    OR firstName LIKE '%$query%'
                    OR lastName LIKE '%$query%'
                    OR phone LIKE '%$query%'
                    OR Email LIKE '%$query%' 
                    OR address LIKE '%$query%'
                    OR birthday LIKE '%$query%'
                    OR Role LIKE '%$query%'
            ";
        }

        // Thêm ORDER BY
        $sql .= " ORDER BY $sort $order";

        // Thêm LIMIT và OFFSET
        $sql .= " LIMIT $limit OFFSET $offset";

        // Thực thi truy vấn
        $result = $this->conn->query($sql);

        // Lấy dữ liệu
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }

    // Hàm để lấy tổng số user
    public function getTotalUsers($query)
    {
        $sql = "SELECT COUNT(*) AS total FROM users";

        // Nếu có query
        if ($query) {
            $sql .= " WHERE Username LIKE '%$query%'
                    OR firstName LIKE '%$query%'
                    OR lastName LIKE '%$query%'
                    OR phone LIKE '%$query%'
                    OR Email LIKE '%$query%'
                    OR address LIKE '%$query%'
                    OR birthday LIKE '%$query%'
                    OR Role LIKE '%$query%'
            ";
        }

        // Thực thi truy vấn
        $result = $this->conn->query($sql);

        return $result->fetch_assoc()['total'];
    }

    // Hàm để insert user
    public function addUser(
        $Avatar,
        $FirstName,
        $LastName,
        $Email,
        $Phone,
        $Country,
        $Birthday,
        $Gender,
        $Username,
        $Password,
        $Role
    ) {
        $stmt = $this->conn->prepare("INSERT INTO users (
            Avatar,
            FirstName,
            LastName,
            Email,
            Phone,
            Address,
            Birthday,
            Gender,
            Username,
            Password,
            Role
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssssssss",
            $Avatar,
            $FirstName,
            $LastName,
            $Email,
            $Phone,
            $Country,
            $Birthday,
            $Gender,
            $Username,
            $Password,
            $Role
        );

        $stmt->execute();

        // Trả về id của user vừa insert
        return $stmt->insert_id;
    }

    // Hàm để xoá user
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}
