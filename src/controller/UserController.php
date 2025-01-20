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

    // Hàm để lấy toàn bộ user
    public function getUserById($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("i", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm để insert user
    public function addUser(
        $Username,
        $ProfileName,
        $Email,
        $Password,
        $Country,
        $IsVerified,
        $Role
    ) {
        $stmt = $this->conn->prepare("INSERT INTO users (
            Username,
            ProfileName,
            Email,
            Password,
            Country,
            IsVerified,
            Role
        ) VALUES (
            :Username,
            :ProfileName,
            :Email,
            :Password,
            :Country,
            :IsVerified,
            :Role
        )");

        $hashPassword = password_hash($Password, PASSWORD_DEFAULT);

        $stmt->bindParam(':Username', $Username);
        $stmt->bindParam(':ProfileName', $ProfileName);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Password', $hashPassword);
        $stmt->bindParam(':Country', $Country);
        $stmt->bindParam(':IsVerified', $IsVerified);
        $stmt->bindParam(':Role', $Role);

        return $stmt->execute();
    }
}
