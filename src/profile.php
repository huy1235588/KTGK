<?php
session_start();
// Giả sử bạn đã có thông tin người dùng từ session hoặc database
$user = [
    'id' => 1,
    'username' => 'example_user',
    'email' => 'user@example.com',
    'avatar' => 'uploads/default_avatar.png'
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
</head>

<body>
    <?php

    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>
    <!-- Content -->
    <article class="container">
        <main class="main">
            <form action="" method="post" enctype="multipart/form-data">
                <h2>Account Information</h2>
                <p><strong>ID:</strong> 2f87e07614bb426aaf4ccb461779a002</p>

                <div class="input-group">
                    <label>Display Name</label>
                    <input type="text" value="huy1235588" disabled>
                    <button class="edit-btn" type="button">✎</button>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="text" value="v****@gmail.com" disabled>
                    <button class="edit-btn" type="button">✎</button>
                </div>

                <div class="input-group">
                    <label>Preferred Communication Language</label>
                    <select>
                        <option>English</option>
                    </select>
                </div>

                <h2>Personal Details</h2>
                <p>Manage your name and contact info. These personal details are private and will not be displayed to other users. View our <a href="#">Privacy Policy</a>.</p>

                <div class="input-group">
                    <label>First Name</label>
                    <input type="text" value="l***ê">
                </div>
                <div class="input-group">
                    <label>Last Name</label>
                    <input type="text" value="h***a">
                </div>

                <h2>Address</h2>
                <div class="input-group">
                    <label>Address Line 1</label>
                    <input type="text">
                </div>
                <div class="input-group">
                    <label>Address Line 2</label>
                    <input type="text">
                </div>
                <div class="input-group">
                    <label>City</label>
                    <input type="text">
                </div>
                <div class="input-group">
                    <label>Region</label>
                    <input type="text">
                </div>
                <div class="input-group">
                    <label>Postal Code</label>
                    <input type="text">
                </div>

                <div class="input-group">
                    <label>Country / Region</label>
                    <input type="text" value="VIETNAM" disabled>
                    <button class="edit-btn">✎</button>
                </div>

                <button class="save-btn">SAVE CHANGES</button>
            </form>

        </main>
    </article>

    <div class="popup" id="edit-popup">
        <div class="popup-content">
            <label for="edit-input">Edit Value:</label>
            <input type="text" id="edit-input">
            <button id="confirm-btn">Confirm</button>
            <button id="cancel-btn">Cancel</button>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script> -->
    <script src="js/profile.js"></script>
</body>

</html>