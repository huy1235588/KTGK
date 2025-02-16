<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
</head>

<body style="background-color: #f0f2f5; background: none;">
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <?php
    // Nạp file UserController.php
    require_once 'controller/UserController.php';
    require_once 'utils/db_connect.php';

    $userId = $_SESSION['user']['id'];
    $conn = MoKetNoi();

    // Khởi tạo UserController
    $userController = new UserController($conn);

    // Lấy thông tin user theo id
    $user = $userController->getUserById($userId);

    // Khởi tạo mảng lưu lỗi
    $errors = [
        'firstName' => '',
        'lastName' => '',
        'phone' => '',
        'email' => '',
        'birthday' => '',
        'userName' => '',
        'password' => ''
    ];

    // Xử lý form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lấy dữ liệu từ form
        $txtAvatar = $_FILES['avatar'];
        $txtFirstName = $_POST['firstName'];
        $txtLastName = $_POST['lastName'];
        $txtEmail = $_POST['email'];
        $txtPhone = $_POST['phone'];
        $txtCountry = $_POST['country'];
        $txtBirthday = $_POST['birthday'];
        $txtGender = $_POST['gender'];
        $txtUsername = $_POST['userName'];
        $txtPassword = $_POST['password'];

        // Xử lý avatar
        // Kiểm tra xem người dùng có chọn avatar mới không
        if ($txtAvatar['size'] > 0) {
            // Tạo id cho avatar từ thời gian hiện tại
            $idAvatar = $userId . '_' . time();
            // Đổi tên file avatar
            $txtAvatar['name'] = $idAvatar . '_' . $txtAvatar['name'];
            // Đường dẫn lưu avatar
            $txtAvatarPath = 'uploads/users/avatar/' . $txtAvatar['name'];

            // Nếu không phải ảnh mặc định thì ảnh cũ sẽ bị xóa
            if ($user['avatar'] !== 'uploads/users/avatar/default_avatar.jpg') {
                unlink($user['avatar']);
            }
        } else {
            $txtAvatarPath = $user['avatar'];
        }

        // Lấy các thông tin khác user
        $data = [
            'id' => $userId,
            'firstName' => $txtFirstName,
            'lastName' => $txtLastName,
            'email' => $txtEmail,
            'phone' => $txtPhone,
            'country' => $txtCountry,
            'birthday' => $txtBirthday,
            'gender' => $txtGender,
            'userName' => $txtUsername,
            'password' => $txtPassword,
            'avatar' => $txtAvatarPath
        ];

        // Lọc các giá trị giống user cũ
        $data = array_filter($data, function ($value, $key) use ($user) {
            return $user[$key] !== $value;
        }, ARRAY_FILTER_USE_BOTH);

        // Cập nhật thông tin user
        $updated = $userController->updateUser($userId, $data);

        // Lưu avatar
        if ($txtAvatar['size'] > 0) {
            move_uploaded_file($txtAvatar['tmp_name'], $txtAvatarPath);
        }

        // Nếu cập nhật thành công thì thông báo
        if ($updated) {
            echo "<script>
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: 'Update successfully!',
                        type: 'success'
                    }));
                    window.location.href = window.location.href.split('?')[0]; // Reload không gửi lại POST data
                </script>";
            exit();
        } else {
            echo "<script>
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: 'Update failed!',
                        type: 'error'
                    }));
                </script>";
        }
    }

    // Danh sách quốc gia
    $countries = [
        "Afghanistan",
        "Åland Islands",
        "Albania",
        "Algeria",
        "American Samoa",
        "Andorra",
        "Angola",
        "Anguilla",
        "Antarctica",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Aruba",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bermuda",
        "Bhutan",
        "Bolivia",
        "Bosnia and Herzegovina",
        "Botswana",
        "Bouvet Island",
        "Brazil",
        "British Indian Ocean Territory",
        "Brunei Darussalam",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Cape Verde",
        "Cayman Islands",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Christmas Island",
        "Cocos (Keeling) Islands",
        "Colombia",
        "Comoros",
        "Congo",
        "Congo, The Democratic Republic of The",
        "Cook Islands",
        "Costa Rica",
        "Cote D'ivoire",
        "Croatia",
        "Cuba",
        "Cyprus",
        "Czech Republic",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Estonia",
        "Ethiopia",
        "Falkland Islands (Malvinas)",
        "Faroe Islands",
        "Fiji",
        "Finland",
        "France",
        "French Guiana",
        "French Polynesia",
        "French Southern Territories",
        "Gabon",
        "Gambia",
        "Georgia",
        "Germany",
        "Ghana",
        "Gibraltar",
        "Greece",
        "Greenland",
        "Grenada",
        "Guadeloupe",
        "Guam",
        "Guatemala",
        "Guernsey",
        "Guinea",
        "Guinea-bissau",
        "Guyana",
        "Haiti",
        "Heard Island and Mcdonald Islands",
        "Holy See (Vatican City State)",
        "Honduras",
        "Hong Kong",
        "Hungary",
        "Iceland",
        "India",
        "Indonesia",
        "Iran, Islamic Republic of",
        "Iraq",
        "Ireland",
        "Isle of Man",
        "Israel",
        "Italy",
        "Jamaica",
        "Japan",
        "Jersey",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Korea, Democratic People's Republic of",
        "Korea, Republic of",
        "Kuwait",
        "Kyrgyzstan",
        "Lao People's Democratic Republic",
        "Latvia",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libyan Arab Jamahiriya",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Macao",
        "Macedonia, The Former Yugoslav Republic of",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Martinique",
        "Mauritania",
        "Mauritius",
        "Mayotte",
        "Mexico",
        "Micronesia, Federated States of",
        "Moldova, Republic of",
        "Monaco",
        "Mongolia",
        "Montenegro",
        "Montserrat",
        "Morocco",
        "Mozambique",
        "Myanmar",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "Netherlands Antilles",
        "New Caledonia",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "Niue",
        "Norfolk Island",
        "Northern Mariana Islands",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Palestinian Territory, Occupied",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Pitcairn",
        "Poland",
        "Portugal",
        "Puerto Rico",
        "Qatar",
        "Reunion",
        "Romania",
        "Russian Federation",
        "Rwanda",
        "Saint Helena",
        "Saint Kitts and Nevis",
        "Saint Lucia",
        "Saint Pierre and Miquelon",
        "Saint Vincent and The Grenadines",
        "Samoa",
        "San Marino",
        "Sao Tome and Principe",
        "Saudi Arabia",
        "Senegal",
        "Serbia",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Georgia and The South Sandwich Islands",
        "Spain",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Svalbard and Jan Mayen",
        "Swaziland",
        "Sweden",
        "Switzerland",
        "Syrian Arab Republic",
        "Taiwan",
        "Tajikistan",
        "Tanzania, United Republic of",
        "Thailand",
        "Timor-leste",
        "Togo",
        "Tokelau",
        "Tonga",
        "Trinidad and Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Turks and Caicos Islands",
        "Tuvalu",
        "Uganda",
        "Ukraine",
        "United Arab Emirates",
        "United Kingdom",
        "United States",
        "United States Minor Outlying Islands",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela",
        "Viet Nam",
        "Virgin Islands, British",
        "Virgin Islands, U.S.",
        "Wallis and Futuna",
        "Western Sahara",
        "Yemen",
        "Zambia",
        "Zimbabwe"
    ];

    ?>

    <script>
        // Kiểm tra thông báo từ sessionStorage khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            const notification = sessionStorage.getItem('notification');

            if (notification) {
                const {
                    message,
                    type
                } = JSON.parse(notification);
                setNotification(message, type); // Gọi hàm hiển thị thông báo của bạn
                sessionStorage.removeItem('notification'); // Xóa thông báo sau khi hiển thị
            }
        });
    </script>

    <!-- Content -->
    <article class="container">
        <main class="main">
            <h1 class="main-title">
                Account Information
            </h1>

            <!-- Form -->
            <form action="" method="post" enctype="multipart/form-data" id="profileForm">
                <table class="card-body">
                    <!-- AVATAR -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label>
                                Avatar
                            </label>
                        </td>

                        <td class="col-form-input col-form-avatar">
                            <!-- Avatar -->
                            <label class="avatar-container" for="avatarUploader">
                                <img
                                    id="avatarImg"
                                    class="avatar-img"
                                    src="<?php echo $user['avatar']; ?>"
                                    alt="Image Description">

                                <input class="avatar-uploader-input"
                                    id="avatarUploader"
                                    name="avatar"
                                    type="file"
                                    accept="image/*">

                                <span class="avatar-uploader-trigger">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
                                    </svg>
                                </span>
                            </label>
                            <!-- End Avatar -->

                            <button id="deleteAvatarBtn" type="button" class="avatar-delete-btn">Delete</button>
                        </td>
                    </tr>
                    <!-- End AVATAR -->

                    <!-- FULL NAME -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="firstNameInput">
                                Full name
                            </label>
                        </td>

                        <td class="col-form-input col-form-name">
                            <div class="form-control-name form-control-wrapper" data-for="firstName">
                                <input
                                    class="form-control form-control-text <?php echo $errors['firstName'] ? 'form-input-error' : ''; ?>"
                                    id="firstNameInput"
                                    type="text"
                                    name="firstName"
                                    placeholder="First name"
                                    value="<?php echo $user['firstName']; ?>"
                                    aria-label="Clarice">

                                <span class="error-message-input"><?php echo $errors['firstName'] ?? ''; ?></span>
                            </div>
                            <div class="form-control-name form-control-wrapper" data-for="lastName">
                                <input
                                    class="form-control form-control-text <?php echo $errors['lastName'] ? 'form-input-error' : ''; ?>"
                                    id="lastNameInput"
                                    type="text"
                                    name="lastName"
                                    placeholder="Last name"
                                    value="<?php echo $user['lastName']; ?>"
                                    aria-label="Boone">

                                <span class="error-message-input"><?php echo $errors['lastName'] ?? ''; ?></span>
                            </div>
                        </td>
                    </tr>
                    <!-- End FULL NAME -->

                    <!-- EMAIL -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="emailInput" class="col-sm-3 col-form-label input-label">
                                Email
                            </label>
                        </td>

                        <td class="col-form-input form-control-wrapper" data-for="email">
                            <input
                                class="form-control <?php echo $errors['email'] ? 'form-input-error' : ''; ?>"
                                id="emailInput"
                                type="email"
                                name="email"
                                placeholder="clarice@example.com"
                                value="<?php echo $user['email']; ?>"
                                aria-label="clarice@example.com">

                            <span class="error-message-input">
                                <?php echo $errors['email'] ?? ''; ?>
                            </span>
                        </td>
                    </tr>
                    <!-- End EMAIL -->

                    <!-- PHONE -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="phoneInput" class="col-sm-3 col-form-label input-label">
                                Phone
                                <span class="input-label-secondary">
                                    (Optional)
                                </span>
                            </label>
                        </td>

                        <td class="col-form-input form-control-wrapper" data-for="phone">
                            <input
                                class="js-masked-input form-control <?php echo $errors['phone'] ? 'form-input-error' : ''; ?>"
                                id="phoneInput"
                                type="text"
                                name="phone"
                                placeholder="+x(xxx)xxx-xx-xx"
                                value="<?php echo $user['phone']; ?>"
                                aria-label="+x(xxx)xxx-xx-xx"
                                maxlength="13">

                            <span class="error-message-input"><?php echo $errors['phone'] ?? ''; ?></span>
                        </td>
                    </tr>
                    <!-- End PHONE -->

                    <!-- COUNTRY -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="countrySelect" class="col-sm-3 col-form-label input-label">
                                Country
                            </label>
                        </td>

                        <td class="col-form-input relative">
                            <select class="select-container"
                                id="countrySelect"
                                name="country"
                                aria-label="Country"
                                value="<?php echo $user['country']; ?>"
                                tabindex="-1">

                                <?php
                                foreach ($countries as $country) {
                                    $selected = ($user['country'] === $country) ? 'selected' : '';
                                    echo "<option value='$country' $selected>$country</option>";
                                }
                                ?>
                            </select>

                            <p class="selected-container" dir="ltr">
                                <span class="selected-text" id="countryText">
                                    United Kingdom
                                    <span class="selected-arrow" role="presentation">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path>
                                        </svg>
                                    </span>
                                </span>
                            </p>
                        </td>
                    </tr>
                    <!-- End COUNTRY -->

                    <!-- BIRTHDAY -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="birthdayInput" class="col-sm-3 col-form-label input-label">
                                Birthday
                            </label>
                        </td>

                        <td class="col-form-input form-control-wrapper" data-for="birthday">
                            <input
                                class="form-control <?php echo $errors['birthday'] ? 'form-input-error' : ''; ?>"
                                id="birthdayInput"
                                type="date"
                                name="birthday"
                                value="<?php echo $user['birthday']; ?>"
                                aria-label="Birthday">

                            <span class="error-message-input"><?php echo $errors['birthday'] ?? ''; ?></span>
                        </td>
                    </tr>

                    <!-- GENDER -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label class="col-sm-3 col-form-label input-label">
                                Gender
                            </label>
                        </td>

                        <td class="col-form-input col-form-radio">
                            <div class="form-control-radio">
                                <input
                                    class="form-control"
                                    id="maleInput"
                                    type="radio"
                                    name="gender"
                                    value="male"
                                    <?php echo $user['gender'] === 'male' ? 'checked' : ''; ?>>
                                <label for="maleInput">
                                    Male
                                </label>
                            </div>
                            <div class="form-control-radio">
                                <input
                                    type="radio"
                                    class="form-control"
                                    name="gender"
                                    value="female"
                                    id="femaleInput"
                                    <?php echo $user['gender'] === 'female' ? 'checked' : ''; ?>>
                                <label for="femaleInput">
                                    Female
                                </label>
                            </div>
                        </td>
                    </tr>

                    <!-- USERNAME -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="usernameInput" class="col-sm-3 col-form-label input-label">
                                Username
                            </label>
                        </td>

                        <td class="col-form-input form-control-wrapper" data-for="userName">
                            <input
                                class="form-control <?php echo $errors['userName'] ? 'form-input-error' : ''; ?>"
                                id="usernameInput"
                                type="text"
                                name="userName"
                                value="<?php echo $user['userName']; ?>"
                                placeholder="Username"
                                aria-label="Username">

                            <span class="error-message-input">
                                <?php echo $errors['userName'] ?? ''; ?>
                            </span>
                        </td>
                    </tr>
                    <!-- End USERNAME -->

                    <!-- PASSWORD -->
                    <tr class="row form-group">
                        <td class="col-form-label">
                            <label for="passwordInput" class="col-sm-3 col-form-label input-label">
                                Password
                            </label>
                        </td>

                        <td class="col-form-input form-input-password form-control-wrapper" data-for="password">
                            <input
                                class="form-control <?php echo $errors['password'] ? 'form-input-error' : ''; ?>"
                                id="passwordInput"
                                type="password"
                                name="password"
                                placeholder="Password"
                                value="<?php echo $user['password']; ?>"
                                aria-label="Password">

                            <span class="error-message-input"><?php echo $errors['password'] ?? ''; ?></span>

                            <!-- Button show password -->
                            <button class="show-password" type="button">
                                <svg class="show-password-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path>
                                    <path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path>
                                </svg>
                                <svg class="hide-password-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.073 12.194 4.212 8.333c-1.52 1.657-2.096 3.317-2.106 3.351L2 12l.105.316C2.127 12.383 4.421 19 12.054 19c.929 0 1.775-.102 2.552-.273l-2.746-2.746a3.987 3.987 0 0 1-3.787-3.787zM12.054 5c-1.855 0-3.375.404-4.642.998L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.298-3.298c2.638-1.953 3.579-4.637 3.593-4.679l.105-.316-.105-.316C21.98 11.617 19.687 5 12.054 5zm1.906 7.546c.187-.677.028-1.439-.492-1.96s-1.283-.679-1.96-.492L10 8.586A3.955 3.955 0 0 1 12.054 8c2.206 0 4 1.794 4 4a3.94 3.94 0 0 1-.587 2.053l-1.507-1.507z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <!-- End PASSWORD -->
                </table>

                <div class="form-group form-group-actions">
                    <button type="submit" class="save-btn" id="saveBtn">
                        Save
                    </button>
                </div>
            </form>

        </main>
    </article>

    <!-- Popup -->
    <div class="popup" id="confirmPopup">
        <div class="popup-content">
            <h2 class="popup-title">Do you want to save changes?</h2>
            <div class="popup-actions">
                <button class="popup-action" id="confirmBtn">Yes</button>
                <button class="popup-action" id="cancelBtn">No</button>
            </div>
        </div>
    </div>

    <!-- Popup Update -->
    <div class="popup" id="updatePopup">
        <div class="popup-content">
            <h2 class="popup-title">Update successfully!</h2>
            <div class="popup-actions">
                <button class="popup-action" id="confirmBtn">OK</button>
            </div>
        </div>
    </div>

    <!-- <div id="cropBox"> -->
    <div id="cropBox" style="display: none;">
        <!-- Header -->
        <h2 class="crop-header">
            Choose img
        </h2>

        <!-- Closer button -->
        <button id="cropBtnClose" class="crop-close-btn">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                <path d="m289.94 256 95-95A24 24 0 0 0 351 127l-95 95-95-95a24 24 0 0 0-34 34l95 95-95 95a24 24 0 1 0 34 34l95-95 95 95a24 24 0 0 0 34-34z"></path>
            </svg>
        </button>

        <!-- Img Crop -->
        <div id="imgCropContainer" class="cropBox-img-container">
            <img id="imageToCrop" class="image-to-crop" />
        </div>

        <!-- Confirm button -->
        <div class="crop-btn">
            <button id="cropBtnCancel" type="button" class="crop-cancel-btn">
                Cancel
            </button>
            <button id="cropBtnSave" type="button" class="crop-save-btn">
                Save
            </button>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>

    <script src="assets/js/copper.min.js"></script>
    <script src="js/profile.js"></script>
</body>

</html>