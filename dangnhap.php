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
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Kiểm tra mật khẩu khớp
        if ($password !== $confirm_password) {
            echo "Mật khẩu không khớp. Vui lòng thử lại.";
            exit;
        }

        // Thực hiện các thao tác lưu trữ dữ liệu
        echo "Đăng ký thành công!";
        // Bạn có thể lưu vào database tại đây
    }
    ?>


    <!-- Content -->
    <main class="container">
        <h1>ĐĂNG NHẬP</h1>
        <form action="register.php" method="post">
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
                        <button type="submit">Đăng ký</button>
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