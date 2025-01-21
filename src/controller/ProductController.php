<?php

require_once __DIR__ . '../../utils/db_connect.php';

class ProductController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Hàm để lấy kiểu dữ liệu của cột 
    public function getColumnTypes($table, $column)
    {
        $stmt = $this->conn->prepare("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->bind_param("ss", $table, $column);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['DATA_TYPE'];
    }

    // Hàm để lấy toàn bộ sản phẩm
    public function getAllProducts()
    {
        $products = $this->conn->query("SELECT * FROM products");

        return $products->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm để lấy sản phẩm theo trang
    public function getProductsByPage($offset, $limit, $columns, $query, $sort = 'id', $order = 'ASC')
    {
        // Xử lý query
        $query = $this->conn->real_escape_string($query);

        // Thêm ký tự % vào trước và sau query
        $query = '%' . $query . '%';

        // Xây dựng điều kiện WHERE
        $whereClause = [];
        foreach ($columns as $column) {
            $whereClause[] = "$column LIKE ?";
        }
        $whereClause = implode(' OR ', $whereClause);

        // Xây dựng câu lệnh SQL
        $sql = "SELECT * 
            FROM products
            WHERE $whereClause
            ORDER BY $sort $order 
            LIMIT ?, ?
        ";

        // Chuẩn bị và gán tham số
        $stmt = $this->conn->prepare($sql);
        
        // Tạo mảng các tham số
        $types = str_repeat('s', count($columns)) . 'ii';
        $params = array_merge(array_fill(0, count($columns), $query), [$offset, $limit]);
        
        // Gọi hàm bind_param với các tham số động
        $stmt->bind_param($types, ...$params);

        // Thực thi câu lệnh
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm để lấy sản phẩm theo ID
    public function getProductById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy các thông tin khác của sản phẩm theo ID
    public function getProductDetailsById($id, $tables)
    {
        $results = [];
        foreach ($tables as $table) {
            $stmt = $this->conn->prepare("SELECT * FROM $table WHERE product_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            $results[$table] = $result;
        }

        return $results;
    }

    // Hàm lấy tổng số sản phẩm
    public function getTotalProducts($query)
    {
        $query = '%' . $query . '%';
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM products WHERE title LIKE ?");
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    // Hàm để insert sản phẩm
    public function addProduct(
        $name,
        $price,
        $quantity,
        $description,
        $image
    ) {
        $stmt = $this->conn->prepare("INSERT INTO products (
            name,
            price,
            quantity,
            description,
            image
        ) VALUES (
            :name,
            :price,
            :quantity,
            :description,
            :image
        )");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
    }

    // Hàm để update sản phẩm
    public function updateProduct(
        $id,
        $name,
        $price,
        $quantity,
        $description,
        $image
    ) {
        $stmt = $this->conn->prepare("UPDATE products SET
            name = :name,
            price = :price,
            quantity = :quantity,
            description = :description,
            image = :image
            WHERE id = :id
        ");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
    }

    // Hàm để xóa sản phẩm
    public function deleteProduct($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // Hàm để tìm kiếm sản phẩm 
    public function searchProduct($keyword)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ?");
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm để lấy sản phẩm theo ID
    public function getProductByCategory($category)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }
}
