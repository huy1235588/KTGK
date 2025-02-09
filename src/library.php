<?php
include 'utils/db_connect.php';
$conn = MoKetNoi();

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("
    SELECT p.* 
    FROM library l
    JOIN products p ON l.product_id = p.id
    WHERE l.user_id = ?
    ORDER BY l.purchased_at DESC
");
$stmt->bind_param('i', $userId);
$stmt->execute();

$result = $stmt->get_result();
$libraryItems = $result->fetch_all(MYSQLI_ASSOC);

// Đóng kết nối
DongKetNoi($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="css/library.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
</head>

<body>
    <?php
    include 'header.php';
    include 'nav.php';
    include 'aside.php';
    ?>

    <main class="container">
        <h1 class="header-title">
            Library
        </h1>

        <?php if (isset($_SESSION['purchase_success'])): ?>
            <div class="alert success">
                Purchase completed successfully!
            </div>
            <?php unset($_SESSION['purchase_success']); ?>
        <?php endif; ?>

        <div class="library-grid">
            <?php foreach ($libraryItems as $item): ?>
                <a class="library-item" href="games/snake-game/index.html">
                    <!-- Image -->
                    <div class="image-container">
                        <img src="<?= htmlspecialchars($item['headerImage']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    </div>

                    <!-- Info -->
                    <div class="info">
                        <!-- Title -->
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="js/library.js"></script>

    <?php include 'footer.php'; ?>
</body>

</html>