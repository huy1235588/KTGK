<?php
session_start();
include_once 'utils/db_connect.php';
$conn = MoKetNoi();

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE userName = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Lấy kết quả
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Lấy dữ liệu người dùng

        // Lưu username vào session
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];

        $stmt->close();
        DongKetNoi($conn);

        // Chuyển hướng tới trang chủ hoặc trang được chuyển hướng
        if (isset($_SESSION['redirect'])) {
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: $redirect");
            exit();
        } else {
            header("Location: trangchu.php");
            exit();
        }
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/dangky.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
</head>

<body class="">
    <!-- Header -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['redirect'])) {
        $_SESSION['redirect'] = $_GET['redirect'];
    }
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <!-- Content -->
    <main class="container">
        <h1>ĐĂNG NHẬP</h1>
        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if ($error): ?>
            <p class="error-login"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="dangnhap.php" method="post">
            <table>
                <tr>
                    <td class="label">Tên đăng nhập:</td>
                    <td><input type="text" name="username" required></td>
                </tr>
                <tr>
                    <td class="label">Mật khẩu:</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit">Đăng nhập</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>

    <?php
    include 'footer.php';
    ?>
</body>

</html>