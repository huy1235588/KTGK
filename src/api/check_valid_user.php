<?php
session_start();

// Nạp file UserController.php
require_once '../controller/UserController.php';
require_once '../utils/db_connect.php';

$userId = $_SESSION['user']['id'];
$conn = MoKetNoi();

// Khởi tạo UserController
$userController = new UserController($conn);

// Lấy thông tin user theo id
$user = $userController->getUserById($userId);

// Set content-type header for json
header('Content-type: application/json');

// Khởi tạo mảng lưu lỗi
$errors = [
    'firstName' => null,
    'lastName' => null,
    'phone' => null,
    'email' => null,
    'birthday' => null,
    'userName' => null,
    'password' => null
];

// Kiểm tra nếu là phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $txtFirstName = $_POST['firstName'];
    $txtLastName = $_POST['lastName'];
    $txtPhone = $_POST['phone'];
    $txtEmail = $_POST['email'];
    $txtBirthday = $_POST['birthday'];
    $txtUsername = $_POST['userName'];
    $txtPassword = $_POST['password'];

    $conn = MoKetNoi();

    // Lấy các thông tin khác user
    $data = [
        'firstName' => $txtFirstName,
        'lastName' => $txtLastName,
        'phone' => $txtPhone,
        'email' => $txtEmail,
        'birthday' => $txtBirthday,
        'userName' => $txtUsername,
        'password' => $txtPassword,
    ];

    // Lọc các giá trị giống user cũ
    $data = array_filter($data, function ($value, $key) use ($user) {
        return $user[$key] !== $value;
    }, ARRAY_FILTER_USE_BOTH);

    // Kiểm tra nếu email đã thay đổi
    if (isset($data['email'])) {
        // Kiểm tra định dạng email
        if (!filter_var($txtEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email is invalid";
        }
        // Kiểm tra email đã tồn tại
        $result_email = $conn->query("SELECT * FROM users WHERE email = '$txtEmail'");
        if ($result_email->num_rows > 0) {
            $errors['email'] = "Email already exists!";
        }
    }

    // Kiểm tra tên đăng nhập đã thay đổi
    if (isset($data['userName'])) {

        // Kiểm tra độ dài username
        if (strlen($txtUsername) < 2) {
            $errors['userName'] = "Username is too short";
        }

        // Kiểm tra username đã tồn tại
        $result_username = $conn->query("SELECT * FROM users WHERE username = '$txtUsername'");
        if ($result_username->num_rows > 0) {
            $errors['userName'] = "Username already exists!";
        }
    }

    // Kiểm tra first name không được để trống nếu có thay đổi
    if (isset($data['firstName']) && empty($txtFirstName)) {
        $errors['firstName'] = "First name is required";
    }

    // Kiểm tra last name không được để trống nếu có thay đổi
    if (isset($data['lastName']) && empty($txtLastName)) {
        $errors['lastName'] = "Last name is required";
    }

    // Kiểm tra định dạng số điện thoại nếu có thay đổi
    if (isset($data['phone'])) {
        if (!preg_match("/^[0-9]{10}$/", $txtPhone)) {
            $errors['phone'] = "Phone number is invalid";
        }
    }

    // Kiểm tra định dạng birthday nếu có thay đổi
    if (isset($data['birthday'])) {
        // Kiểm tra birthday không được để trống
        if (empty($txtBirthday)) {
            $errors['birthday'] = "Birthday is required";
        }
        // Kiểm tra birthday không được lớn hơn ngày hiện tại
        elseif (strtotime($txtBirthday) > strtotime(date('Y-m-d'))) {
            $errors['birthday'] = "Birthday is invalid";
        }
    }

    // Kiểm tra mật khẩu nếu có thay đổi
    if (isset($data['password'])) {
        if (strlen($txtPassword) < 2) {
            $errors['password'] = "Password is too short";
        }
    }

    // Kiểm tra nếu có lỗi
    if (!empty(array_filter($errors))) {
        // Xoá các error null
        $errors = array_filter($errors);

        echo json_encode(['errors' => $errors]);
    } else {
        // Nếu không có lỗi
        echo json_encode(['success' => 'Update user successfully']);
    }
}
