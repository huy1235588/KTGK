<?php
session_start();
ob_start();
include_once 'utils/db_connect.php';
include 'components/notification.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

    <?php
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
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['username'] = $user['userName'];
            $_SESSION['user']['role'] = $user['role'];
            $_SESSION['user']['avatar'] = $user['avatar'];

            $stmt->close();
            DongKetNoi($conn);

            // Tạo thông báo đăng nhập thành công
            echo "<script>setNotification('Login successfully!', 'success');</script>";
            
            // Chuyển hướng tới trang chủ hoặc trang được chuyển hướng
            if (isset($_SESSION['redirect'])) {
                $redirect = $_SESSION['redirect'];
                unset($_SESSION['redirect']);
                echo "<script>location.href='$redirect';</script>";
                exit();
            } else {
                echo "<script>location.href='index.php';</script>";
                exit();
            }
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu.";
        }
    }
    ?>

    <!-- Content -->
    <main class="container">
        <h1>LOGIN</h1>
        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if ($error): ?>
            <p class="error-login"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="dangnhap.php" method="post">
            <table>
                <tr>
                    <td class="label">Username:</td>
                    <td><input type="text" name="username" required></td>
                </tr>
                <tr>
                    <td class="label">Password:</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="button-submit" type="submit">Login</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>

    <?php
    include 'footer.php';
    ob_end_flush();
    ?>
</body>

</html>