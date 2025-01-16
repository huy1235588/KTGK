<?php
include '../utils/db_connect.php';
// Mở kết nối
$conn = MoKetNoi();

// Xử lý yêu cầu
$method = $_SERVER['REQUEST_METHOD'];

// Nếu là GET
if ($method === 'GET') {
    // Lấy id từ query string
    $id = $_GET['id'];

    // Truy vấn sản phẩm theo id
    $sqlProduct = "SELECT *
    FROM products
    WHERE id = $id";

    // Thực thi truy vấn
    $product = $conn->query($sqlProduct)->fetch_assoc();

    // Truy vấn screenshots theo id
    $sqlScreenshots = "SELECT *
    FROM product_screenshots
    WHERE product_id = $id
    LIMIT 4";

    // Thực thi truy vấn
    $screenshots = $conn->query($sqlScreenshots);

    // Truy vấn tags 
    $sqlTags = "SELECT *
    FROM product_tags
    WHERE product_id = $id";

    // Thực thi truy vấn
    $tags = $conn->query($sqlTags);

    // Đóng kết nối
    DongKetNoi($conn);
} else {
    // Trả về lỗi
    http_response_code(405);
    echo 'Method not allowed';
}
?>

<div id="hover-product-<?php echo $product['id']; ?>" class="hover_product">
    <h4 class="hover_title">
        <?php echo $product['title']; ?>
    </h4>
    <div class="hover_release">
        <span>
            Released:
            <?php
            // 2024-07-02 00:00:00 -> 02/07/2024
            $releaseDate = date('d/m/Y', strtotime($product['releaseDate']));
            echo $releaseDate;
            ?>
        </span>
    </div>
    <div class="hover_screenshots">
        <?php foreach ($screenshots as $screenshot): ?>
            <div class="screenshot" style="background-image: url(<?php echo $screenshot['screenshot']; ?>)">
            </div>
        <?php endforeach; ?>
    </div>
    <div class="hover_body">
        User tags:
        <div class="hover_tag_row">
            <?php foreach ($tags as $tag): ?>
                <div class="app_tag"><?php echo $tag['tag']; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>