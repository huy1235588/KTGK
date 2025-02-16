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

    // Hàm để tìm user theo username
    public function findUserByUsername($username)
    {
        $username = '%' . $username . '%';

        $sql = "SELECT *
                FROM users u
                WHERE userName LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

    // Hàm để cập nhật user
    public function updateUser($id, $data)
    {
        // Danh sách các cột được phép cập nhật và kiểu dữ liệu tương ứng
        $allowedColumns = [
            'avatar' => 's',
            'firstname' => 's',
            'lastname' => 's',
            'email' => 's',
            'phone' => 's',
            'address' => 's',
            'country' => 's',
            'birthday' => 's',
            'gender' => 's',
            'username' => 's',
            'password' => 's',
            'role' => 's'
        ];

        // Chuẩn bị các phần của câu lệnh SQL
        $setClause = [];
        $bindTypes = '';
        $bindValues = [];

        foreach ($data as $key => $value) {
            // Kiểm tra cột có được phép không
            if (!isset($allowedColumns[$key])) continue;

            $setClause[] = "`$key` = ?";
            $bindTypes .= $allowedColumns[$key];
            $bindValues[] = $value;
        }

        // Không có trường hợp nào để cập nhật
        if (empty($setClause)) return false;

        // Thêm id vào cuối mảng bind values
        $bindTypes .= 'i';
        $bindValues[] = $id;

        // Tạo câu lệnh SQL
        $sql = "UPDATE users SET " . implode(', ', $setClause) . " WHERE id = ?";

        // Thực thi câu lệnh
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($bindTypes, ...$bindValues);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}
