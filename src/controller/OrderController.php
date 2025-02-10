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

    // Lấy thông tin chi tiết đơn hàng
    public function getOrderDetailByPage($orderId, $offset, $limit, $columns, $query, $sort, $order)
    {
        $sql = "SELECT *, p.id AS product_id
                FROM order_details od JOIN products p ON od.product_id = p.id
                WHERE order_id = $orderId ";

        // Nếu có query
        if ($query) {
            $sql .= "AND (product_id LIKE '%$query%'
                    OR title LIKE '%$query%' 
                    OR price LIKE '%$query%')
            ";
        }

        // Thêm ORDER BY
        if ($sort === 'id') {
            $sort = 'p.id';
        }
        $sql .= " ORDER BY $sort $order";

        // Thêm LIMIT và OFFSET
        $sql .= " LIMIT $limit OFFSET $offset";

        // Thực thi truy vấn
        $result = $this->conn->query($sql);

        // Lấy dữ liệu
        $orderDetails = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orderDetails[] = $row;
            }
        }

        return $orderDetails;
    }

    // Lấy tổng số chi tiết đơn hàng
    public function getTotalOrderDetail($query, $orderId)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM order_details od JOIN products p ON od.product_id = p.id
                WHERE order_id = $orderId ";

        // Nếu có query
        if ($query) {
            $sql .= "AND (product_id LIKE '%$query%'
                    OR title LIKE '%$query%'
                    OR price LIKE '%$query%')
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
