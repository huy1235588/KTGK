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
        firstName VARCHAR(100) NOT NULL,
        lastName VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        address VARCHAR(255),
        gender ENUM('Nam', 'Nữ') NOT NULL,
        birthday DATE,
        avatar VARCHAR(255) DEFAULT 'uploads/default_avatar.png',
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        role varchar(10) DEFAULT 'user' 
    )";

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

    // Bảng product_developers
    $sqlProductDevelopers = "CREATE TABLE IF NOT EXISTS product_developers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        developer VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    // Bảng product_publishers
    $sqlProductPublishers = "CREATE TABLE IF NOT EXISTS product_publishers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        publisher VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    // Bảng product_platforms
    $sqlProductPlatforms = "CREATE TABLE IF NOT EXISTS product_platforms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        platform VARCHAR(100) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    // Bảng product_screenshots
    $sqlProductScreenshots = "CREATE TABLE IF NOT EXISTS product_screenshots (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        screenshot VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    // Bảng product_videos
    $sqlProductVideos = "CREATE TABLE IF NOT EXISTS product_videos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        mp4 VARCHAR(255) NOT NULL,
        webm VARCHAR(255) NOT NULL,
        thumbnail VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    // Bảng genres
    $sqlGenres = "CREATE TABLE IF NOT EXISTS genres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )";

    // Bảng product_genres
    $sqlProductGenres = "CREATE TABLE IF NOT EXISTS product_genres (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        genre_id INT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
    )";

    // Bảng tags
    $sqlTags = "CREATE TABLE IF NOT EXISTS tags (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )";

    // Bảng product_tags
    $sqlProductTags = "CREATE TABLE IF NOT EXISTS product_tags (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        tag_id INT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
    )";

    // Bảng features
    $sqlFeatures = "CREATE TABLE IF NOT EXISTS features (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )";

    // Bảng product_features
    $sqlProductFeatures = "CREATE TABLE IF NOT EXISTS product_features (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        feature_id INT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
    )";

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

    // Bảng product_achievements
    $sqlProductAchievements = "CREATE TABLE IF NOT EXISTS product_achievements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        title VARCHAR(255),
        percent DECIMAL(5, 2),
        description TEXT,
        image VARCHAR(255),
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    // Bảng languages
    $sqlLanguages = "CREATE TABLE IF NOT EXISTS languages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )";

    // Bảng product_languages
    $sqlProductLanguages = "CREATE TABLE IF NOT EXISTS product_languages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        language_id INT NOT NULL,
        interface BOOLEAN,
        fullAudio BOOLEAN,
        subtitles BOOLEAN,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
    )";

    $sqlArray = [
        $sqlUsers,
        $sqlProducts,
        $sqlProductDevelopers,
        $sqlProductPublishers,
        $sqlProductPlatforms,
        $sqlProductScreenshots,
        $sqlProductVideos,
        $sqlGenres,
        $sqlProductGenres,
        $sqlTags,
        $sqlProductTags,
        $sqlFeatures,
        $sqlProductFeatures,
        $sqlProductSystemRequirements,
        $sqlProductReviews,
        $sqlProductAchievements,
        $sqlLanguages,
        $sqlProductLanguages
    ];

    foreach ($sqlArray as $sql) {
        if ($conn->query($sql) === TRUE) {
            echo "Tạo bảng thành công!<br>";
        } else {
            die("Lỗi khi tạo bảng: " . $conn->error);
        }
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

// Hàm chèn dữ liệu từ file sql
function ChenDuLieuTuFileSQL($sqlFile = 'data.sql')
{
    echo "Chèn dữ liệu từ file '$sqlFile'...<br>";

    include_once 'db_connect.php';
    $conn = MoKetNoi();

    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    // Đọc file SQL
    $sqlFileContent = file_get_contents($sqlFile);

   // Thực thi câu lệnh SQL
    if ($conn->multi_query($sqlFileContent) === TRUE) {
        echo "Dữ liệu đã được chèn thành công từ file '$sqlFile'.<br>";
    } else {
        die("Lỗi khi chèn dữ liệu từ file '$sqlFile': " . $conn->error);
    }

    // Đóng kết nối
    DongKetNoi($conn);
}

// Gọi hàm tạo database và table
TaoDatabaseVaTable();

echo "Database và các bảng đã được tạo thành công!<br><br>";

// Danh sách các file chứa dữ liệu cần chèn
$list_tables = [
    "../../database/insert_user.sql",
    "../../database/insert_product.sql",
    "../../database/insert_developer.sql",
    "../../database/insert_publisher.sql",
    "../../database/insert_platform.sql",
    "../../database/insert_screenshots.sql",
    "../../database/insert_videos.sql",
    "../../database/insert_genres.sql",
    "../../database/insert_product_genres.sql",
    "../../database/insert_tags.sql",
    "../../database/insert_product_tags.sql",
    "../../database/insert_features.sql",
    "../../database/insert_product_features.sql",
    "../../database/insert_systemRequirements.sql",
    "../../database/insert_achievements.sql",
    "../../database/insert_languages.sql",
    "../../database/insert_product_languages.sql"
];

foreach ($list_tables as $table) {
    ChenDuLieuTuFileSQL($table);
}

echo "Dữ liệu đã được chèn thành công!";