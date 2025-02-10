<?php

require_once __DIR__ . '../../utils/db_connect.php';

class OrderController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Lấy tổng số đơn hàng
    public function getOrdersByPage($offset, $limit, $columns, $query, $sort, $order)
    {
        $sql = "SELECT * FROM orders";

        // Nếu có query
        if ($query) {
            $sql .= " WHERE id LIKE '%$query%' 
                    OR user_id LIKE '%$query%'
                    OR totalAmount LIKE '%$query%' 
                    OR paymentMethod LIKE '%$query%' 
                    OR status LIKE '%$query%'
                    OR createdAt LIKE '%$query%'
            ";
        }

        // Thêm ORDER BY
        $sql .= " ORDER BY $sort $order";

        // Thêm LIMIT và OFFSET
        $sql .= " LIMIT $limit OFFSET $offset";

        // Thực thi truy vấn
        $result = $this->conn->query($sql);

        // Lấy dữ liệu
        $orders = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }

        return $orders;
    }

    // Lấy tổng số đơn hàng
    public function getTotalOrders($query)
    {
        $sql = "SELECT COUNT(*) AS total FROM orders";

        // Nếu có query
        if ($query) {
            $sql .= " WHERE id LIKE '%$query%'
                    OR user_id LIKE '%$query%'
                    OR totalAmount LIKE '%$query%'
                    OR paymentMethod LIKE '%$query%'
                    OR status LIKE '%$query%'
                    OR createdAt LIKE '%$query%'
            ";
        }

        // Thực thi truy vấn
        $result = $this->conn->query($sql);

        // Lấy dữ liệu
        $total = 0;
        if ($result->num_rows > 0) {
            $total = $result->fetch_assoc()['total'];
        }

        return $total;
    }
}
