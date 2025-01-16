<?php
function TaoDatabaseVaTable()
{
    include_once 'db_connect.php';
    $conn = MoKetNoi();

    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    // SQL tạo bảng users nếu chưa tồn tại
    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        address VARCHAR(255),
        gender ENUM('Nam', 'Nữ') NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        role varchar(10) DEFAULT 'user' 
    )";

    if ($conn->query($sqlUsers) === TRUE) {
        echo "Bảng 'users' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'users': " . $conn->error);
    }

    // SQL tạo bảng products nếu chưa tồn tại
    $sqlProducts = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        type VARCHAR(255),
        description TEXT,
        detail TEXT,
        price DECIMAL(10, 3) DEFAULT 0,
        discount DECIMAL(10, 3) DEFAULT 0,
        discountStartDate DATETIME,
        discountEndDate DATETIME,
        releaseDate DATETIME,
        rating INT,
        isActive BOOLEAN DEFAULT TRUE,
        headerImage VARCHAR(255) NOT NULL,
        createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sqlProducts) === TRUE) {
        echo "Tables created successfully";
    } else {
        die("Error creating tables: " . $conn->error);
    }

    // Bảng product_developers
    $sqlProductDevelopers = "CREATE TABLE IF NOT EXISTS product_developers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        developer VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductDevelopers) === TRUE) {
        echo "Bảng 'product_developers' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_developers': " . $conn->error);
    }

    // Bảng product_publishers
    $sqlProductPublishers = "CREATE TABLE IF NOT EXISTS product_publishers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        publisher VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductPublishers) === TRUE) {
        echo "Bảng 'product_publishers' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_publishers': " . $conn->error);
    }

    // Bảng product_platforms
    $sqlProductPlatforms = "CREATE TABLE IF NOT EXISTS product_platforms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        platform VARCHAR(100) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductPlatforms) === TRUE) {
        echo "Bảng 'product_platforms' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_platforms': " . $conn->error);
    }

    // Bảng product_screenshots
    $sqlProductScreenshots = "CREATE TABLE IF NOT EXISTS product_screenshots (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        screenshot VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductScreenshots) === TRUE) {
        echo "Bảng 'product_screenshots' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_screenshots': " . $conn->error);
    }

    // Bảng product_videos
    $sqlProductVideos = "CREATE TABLE IF NOT EXISTS product_videos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        mp4 VARCHAR(255) NOT NULL,
        webm VARCHAR(255) NOT NULL,
        thumbnail VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductVideos) === TRUE) {
        echo "Bảng 'product_videos' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_videos': " . $conn->error);
    }

    // Bảng product_genres
    $sqlProductGenres = "CREATE TABLE IF NOT EXISTS product_genres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        genre VARCHAR(100) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductGenres) === TRUE) {
        echo "Bảng 'product_genres' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_genres': " . $conn->error);
    }

    // Bảng product_tags
    $sqlProductTags = "CREATE TABLE IF NOT EXISTS product_tags (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        tag VARCHAR(100) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductTags) === TRUE) {
        echo "Bảng 'product_tags' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_tags': " . $conn->error);
    }

    // Bảng product_features
    $sqlProductFeatures = "CREATE TABLE IF NOT EXISTS product_features (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        feature VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductFeatures) === TRUE) {
        echo "Bảng 'product_features' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_features': " . $conn->error);
    }

    // Bảng product_system_requirements
    $sqlProductSystemRequirements = "CREATE TABLE IF NOT EXISTS product_system_requirements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        platform ENUM('win', 'mac', 'linux') NOT NULL,
        title VARCHAR(255),
        minimum TEXT,
        recommended TEXT,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductSystemRequirements) === TRUE) {
        echo "Bảng 'product_system_requirements' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_system_requirements': " . $conn->error);
    }

    // Bảng product_reviews
    $sqlProductReviews = "CREATE TABLE IF NOT EXISTS product_reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        isRecommended BOOLEAN DEFAULT TRUE,
        isPurchased BOOLEAN DEFAULT FALSE,
        createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    if ($conn->query($sqlProductReviews) === TRUE) {
        echo "Bảng 'product_reviews' đã được tạo hoặc đã tồn tại.<br>";
    } else {
        die("Lỗi khi tạo bảng 'product_reviews': " . $conn->error);
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

// Hàm chèn dữ liệu từ file sql
function ChenDuLieuTuFileSQL($sqlFile = 'data.sql')
{
    include_once 'db_connect.php';
    $conn = MoKetNoi();

    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    // Đọc file sql
    // $sqlFile = file_get_contents($sqlFile);

    // Đọc file SQL
    $sqlFileContent = file_get_contents($sqlFile);

    // Sử dụng biểu thức chính quy để chia các câu lệnh SQL theo dấu `;`
    $sqlArray = preg_split('/;\s*$/m', $sqlFileContent);

    // Thực thi từng câu lệnh SQL
    foreach ($sqlArray as $sql) {
        if (trim($sql)) {
            if ($conn->query($sql) === TRUE) {
                echo "Câu lệnh SQL đã được thực thi thành công: " . substr($sql, 0, 50) . "...<br>";
            } else {
                echo "Lỗi khi thực thi câu lệnh SQL: " . $conn->error . "<br>";
            }
        }
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

// Gọi hàm tạo database và table
TaoDatabaseVaTable();

// Gọi hàm chèn dữ liệu từ file sql
ChenDuLieuTuFileSQL("../database/insert.sql");
