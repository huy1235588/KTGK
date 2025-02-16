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

    <?php
    // Nếu MoKetNoi() đã tồn tại
    if (!function_exists('MoKetNoi')) {
        include 'utils/db_connect.php';
    }

    // Khởi tạo mảng lưu lỗi
    $errors = [
        'firstName' => '',
        'lastName' => '',
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
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
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
            $errors['email'] = "Email already exists!";
        }

        // Kiểm tra tên đăng nhập đã tồn tại
        $result_username = $conn->query("SELECT * FROM users WHERE username = '$username'");
        if ($result_username->num_rows > 0) {
            $errors['username'] = "Username already exists!";
        }

        // Kiểm tra các điều kiện khác như mật khẩu, số điện thoại...
        if (empty($firstName)) {
            $errors['name'] = "First name is required";
        }

        if (empty($lastName)) {
            $errors['lastName'] = "Last name is required";
        }

        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $errors['phone'] = "Phone number is invalid";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email is invalid";
        }

        if (strlen($password) < 2) {
            $errors['password'] = "Password is too short";
        }

        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match";
        }

        if (empty(array_filter($errors))) {
            // Insert người dùng vào database
            $sql = "INSERT INTO users (firstName, lastName, phone, email, address, gender, username, password) 
        VALUES ('$firstName', '$lastName', '$phone', '$email', '$address', '$gender', '$username', '$password')";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute()) {
                // Tạo thông báo đăng ký thành công
                echo "<script>setNotification('Sign up successfully!', 'success');</script>";

                // Đóng kết nói database
                $stmt->close();
                $conn->close();

                // Chuyển hướng đến trang đăng nhập
                echo "<script>location.href='dangnhap.php';</script>";
                exit();
            } else {
                $errors['general'] = "Error: " . $conn->error;
            }
        }
    }
    ?>

    <!-- Content -->
    <main class="container">
        <h1>Sign up</h1>
        <form action="dangky.php" method="post">
            <table>
                <tr>
                    <td class="label">
                        <label for="firstName">First Name:</label>
                    </td>
                    <td>
                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['firstName'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="lastName">Last Name:</label>
                    </td>
                    <td>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['lastName'] ?? ''; ?></span>
                </tr>
                <tr>
                    <td class="label">
                        <label for="phone">
                            Phone:
                        </label>
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
                        <label for="address">Address:</label>
                    </td>
                    <td>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label">Gender:</td>
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
                        <label for="username">Username:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['username'] ? 'input-error' : ''; ?>" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                        <span class="error"><?php echo $errors['username'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="password">Password:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['password'] ? 'input-error' : ''; ?>" type="password" id="password" name="password" required>
                        <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <label for="confirm_password">Confirm password:</label>
                    </td>
                    <td>
                        <input class="<?php echo $errors['password'] ? 'input-error' : ''; ?>" type="password" id="confirm_password" name="confirm_password" required>
                        <span class="error"><?php echo $errors['confirm_password'] ?? ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="button-submit" type="submit">
                            Sign up
                        </button>
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