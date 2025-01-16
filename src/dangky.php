<?php
// Nếu MoKetNoi() đã tồn tại
if (!function_exists('MoKetNoi')) {
    include 'utils/db_connect.php';
}

// Khởi tạo mảng lưu lỗi
$errors = [
    'name' => '',
    'phone' => '',
    'email' => '',
    'address' => '',
    'gender' => '',
    'username' => '',
    'password' => '',
    'confirm_password' => ''
];

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $conn = MoKetNoi();

    // Kiểm tra email đã tồn tại
    $result_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result_email->num_rows > 0) {
        $errors['email'] = "Email đã tồn tại!";
    }

    // Kiểm tra tên đăng nhập đã tồn tại
    $result_username = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result_username->num_rows > 0) {
        $errors['username'] = "Tên đăng nhập đã tồn tại!";
    }

    // Kiểm tra các điều kiện khác như mật khẩu, số điện thoại...
    if (empty($name)) {
        $errors['name'] = "Họ tên không được để trống";
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors['phone'] = "Số điện thoại không hợp lệ";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    }

    if (strlen($password) < 3) {
        $errors['password'] = "Mật khẩu phải có ít nhất 3 ký tự";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Mật khẩu nhập lại không khớp";
    }

    if (empty(array_filter($errors))) {

        // Insert người dùng vào database
        $stmt = $conn->prepare("INSERT INTO users (name, phone, email, address, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $phone, $email, $address, $gender, $username, $password);

        if ($stmt->execute()) {
            require 'components/notification.php';

            // Tạo thông báo đăng ký thành công
            setNotification('Đăng ký thành công!', 'success');

            // Đóng kết nói database
            $stmt->close();
            $conn->close();

            // Chuyển hướng đến trang đăng nhập
            header("Location: dangnhap.php");
            exit();
        } else {
            $errors['general'] = "Đã xảy ra lỗi khi đăng ký, vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/dangky.css">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
</head>

<body>
    <!-- Header -->
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <!-- Content -->
    <main class="container">
        <h1>ĐĂNG KÝ</h1>
        <form action="dangky.php" method="post">
            <table>
                <tr>
                    <td class="label">
                        <label for="name">Họ tên:</label>
                    </td>
                    <td>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['name'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="phone">Số điện thoại:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['phone'] ? 'input-error' : ''; ?>" type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['phone'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="email">Email:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['email'] ? 'input-error' : ''; ?>" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="address">Địa chỉ:</label>
                    </td>
                    <td>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label">Giới tính:</td>
                    <td>
                        <input type="radio" id="male" name="gender" value="Nam" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Nam') ? 'checked' : '' ?>>
                        <label for="male">Nam</label>
                        <input type="radio" id="female" name="gender" value="Nữ" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Nữ') ? 'checked' : '' ?>>
                        <label for="female">Nữ</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="username">Tên đăng nhập:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['username'] ? 'input-error' : ''; ?>" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['username'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="password">Mật khẩu:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['password'] ? 'input-error' : ''; ?>" type="password" id="password" name="password" required>
                        <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="confirm_password">Nhập lại mật khẩu:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['password'] ? 'input-error' : ''; ?>" type="password" id="confirm_password" name="confirm_password" required>
                        <span class="error"><?php echo $errors['confirm_password'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit">Đăng ký</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <script src="js/dangky.js"></script>
    <?php
    include 'footer.php';
    ?>
</body>

</html>