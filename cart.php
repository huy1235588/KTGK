<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql    = "SELECT * FROM cart";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart Page</title>
</head>
<body>
    <h1>Your Cart</h1>
    <?php if ($result && $result->num_rows > 0) : ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Cart ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['cart_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['price']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>Không có dữ liệu trong giỏ hàng.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>