<?php
// Tiêu đề của trang
$title = "Add Product";
// Link phụ để hiển thị active
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Add Product'];

ob_start();
?>
<link rel="stylesheet" href="product-add.css">
<link rel="stylesheet" href="/components/popupInput.css">
<link rel="stylesheet" href="/assets/css/quill.snow.css">
<link rel="stylesheet" href="/assets/css/quill.bubble.css">
<link rel="stylesheet" href="/assets/css/dropzone.css">

<?php
// Nạp file ProductController.php
require_once '../../../../../controller/ProductController.php';
require_once '../../../../../utils/db_connect.php';
require '../../../../vendor/autoload.php';

// Import Dotenv
use Dotenv\Dotenv;

// Load file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
$dotenv->load();

// Import Cloudinary class
use Cloudinary\Cloudinary;

// Mở kết nối
$conn = MoKetNoi();

// Khởi tạo ProductController
$productController = new ProductController($conn);
// Lấy dữ liệu từ database
$types = $productController->getTypes();
$platforms = $productController->getPlatforms();
$genres = $productController->getGenres();
$tags = $productController->getTags();
$features = $productController->getFeatures();

// Khởi tạo mảng lỗi
$errors = [
    'title' => '',
    'type' => '',
    'description' => '',
    'details' => '',
    'price' => '',
    'discount' => '',
    'discount-start-date' => '',
    'discount-end-date' => '',
    'release-date' => '',
    'headerImage' => '',
    'screenshots' => '',
];

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*===================================================================

                            Lấy dữ liệu

    ===================================================================*/
    $txtTitle = htmlspecialchars($_POST['title']);
    $txtType = htmlspecialchars($_POST['type']);
    $txtDescription = htmlspecialchars($_POST['description']);

    // Xử lý details
    $txtDetails = '';
    if (isset($_POST['details'])) {
        $txtDetails = $_POST['details'];
    }

    $txtPrice = htmlspecialchars($_POST['price']);
    $txtDiscount = htmlspecialchars($_POST['discount']);

    // Nếu discount > 0 thì lấy discount start và end date
    $txtDiscountStartDate = null;
    $txtDiscountEndDate = null;
    if ($txtDiscount > 0) {
        $txtDiscountStartDate = htmlspecialchars($_POST['discount-start-date']);
        $txtDiscountEndDate = htmlspecialchars($_POST['discount-end-date']);
    }

    $txtReleaseDate = htmlspecialchars($_POST['release-date']);
    $txtDeveloper = htmlspecialchars($_POST['developer']);
    $txtPublisher = htmlspecialchars($_POST['publisher']);
    $txtPlatform = htmlspecialchars($_POST['platform']);
    $txtGenres = htmlspecialchars($_POST['genres']);
    $txtTags = htmlspecialchars($_POST['tags']);
    $txtFeatures = htmlspecialchars($_POST['features']);

    // Xử lý ảnh headerImage
    $txtHeaderImage = '';
    if (isset($_FILES['headerImage'])) {
        $txtHeaderImage = '';
    }
    // Nếu có headerImage có https:// hoặc http:// thì lấy luôn
    else if (strpos($_POST['headerImage'], 'https://') !== false || strpos($_POST['headerImage'], 'http://') !== false) {
        $txtHeaderImage = htmlspecialchars($_POST['headerImage']);
    }
    // Nếu headerImage không rỗng
    else if ($_POST['headerImage'] !== '') {
        // Nếu không có thì thêm uploads/images vào trước
        $txtHeaderImage = 'uploads/images/' . htmlspecialchars($_POST['headerImage']);
    }

    $txtScreenshots = $_FILES['screenshots'];
    $txtVideos = $_FILES['videos'];

    /*===================================================================

                            Xử lý dữ liệu

    ===================================================================*/
    // Xử lý isActive
    $txtIsActive = isset($_POST['isActive']) ? 1 : 0;

    // Kiểm tra dữ liệu không được để trống
    foreach ($_POST as $key => $value) {
        if (!isset($value) || trim($value) === "") {
            $errors[$key] = 'This field is required';
        }
    }

    // Kiểm tra headerImage file không được để trống
    if ($txtHeaderImage === '') {
        $errors['headerImage'] = 'This field is required';
    }

    // Kiểm tra title đã tồn tại
    $result_title = $conn->query("SELECT * FROM products WHERE title = '$txtTitle'");
    if ($result_title->num_rows > 0) {
        $errors['title'] = 'Title already exists';
    }

    // Kiểm tra Screenshot có ít nhất 2 ảnh
    if (count($txtScreenshots) < 2) {
        $errors['screenshots'] = 'At least 2 screenshots';
    }

    // Kiểm tra không có lỗi
    if (empty(array_filter($errors))) {
        /*===================================================================

                            Thêm sản phẩm

        ===================================================================*/
        // Nếu không có lỗi thì hiển thị dữ liệu
        // echo 'title: ' . $txtTitle . '<br>';
        // echo 'type: ' . $txtType . '<br>';
        // echo 'description: ' . $txtDescription . '<br>';
        // echo 'details: ' . $txtDetails . '<br>';
        // echo 'price: ' . $txtPrice . '<br>';
        // echo 'discount: ' . $txtDiscount . '<br>';
        // echo 'discount-start-date: ' . $txtDiscountStartDate . '<br>';
        // echo 'discount-end-date: ' . $txtDiscountEndDate . '<br>';
        // echo 'release-date: ' . $txtReleaseDate . '<br>';
        // echo 'developer: ' . $txtDeveloper . '<br>';
        // echo 'publisher: ' . $txtPublisher . '<br>';
        // echo 'platform: ' . $txtPlatform . '<br>';
        // echo 'genres: ' . $txtGenres . '<br>';
        // echo 'tags: ' . $txtTags . '<br>';
        // echo 'features: ' . $txtFeatures . '<br>';
        // echo 'headerImage: ' . $txtHeaderImage . '<br>';
        // echo 'isActive: ' . $txtIsActive . '<br>';

        // Thực hiện thêm sản phẩm
        $productId =  $productController->addProduct(
            $txtTitle,
            $txtType,
            $txtDescription,
            $txtDetails,
            $txtPrice,
            $txtDiscount,
            $txtDiscountStartDate,
            $txtDiscountEndDate,
            $txtReleaseDate,
            $txtHeaderImage,
            $txtIsActive
        );

        // Nếu thêm sản phẩm thành công
        if (isset($productId)) {
            /*===================================================================

                                    Upload file

            ===================================================================*/
            // Khởi tạo Cloudinary
            $cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL']);

            /**
             * Uploads a file to Cloudinary.
             *
             * @param string $filePath Path to the file.
             * @param string $folder Cloudinary folder name.
             * @param string $resourceType Type of resource (e.g., 'image').
             * @param string $publicId Public ID for the uploaded file.
             * @return array|string Returns an array with 'public_id' and 'url' on success, or an error message.
             */
            function uploadFileToCloudinary($filePath, $folder, $resourceType, $publicId)
            {
                global $cloudinary;

                try {
                    // Upload file lên Cloudinary
                    $uploadedFile = $cloudinary->uploadApi()->upload($filePath, [
                        "public_id" => $publicId,
                        "folder" => $folder,
                        "resource_type" => $resourceType
                    ]);

                    // Trả về URL của file đã upload
                    return [
                        'public_id' => $uploadedFile['public_id'],
                        'url' => $uploadedFile['secure_url']
                    ];
                } catch (\Cloudinary\Api\Exception\ApiError $e) {
                    // Xử lý lỗi nếu upload thất bại
                    return 'Error: ' . $e->getMessage();
                }
            }

            /**
             * Generates a thumbnail from a video file.
             *
             * @param string $videoPath Path to the video file.
             * @param string $thumbnailPath Path to save the thumbnail.
             * @param int $timeInSeconds Time in seconds to generate the thumbnail.
             * @return string Returns the path to the generated thumbnail on success, or an error message.
             */
            function generateThumbnail($videoPath, $thumbnailPath, $timeInSeconds = 1)
            {
                // Kiểm tra FFmpeg đã cài đặt chưa
                $ffmpegPath = "ffmpeg"; // Nếu cần, đổi thành đường dẫn đầy đủ của ffmpeg
                if (!shell_exec("$ffmpegPath -version")) {
                    return "FFmpeg not installed or not found.";
                }

                // Lệnh FFmpeg để trích xuất ảnh từ video
                $cmd = "$ffmpegPath -i $videoPath -ss $timeInSeconds -vframes 1 -q:v 2 $thumbnailPath 2>&1";
                shell_exec($cmd);

                // Kiểm tra file thumbnail có được tạo hay không
                if (!file_exists($thumbnailPath)) {
                    return "Error generating thumbnail.";
                }

                return $thumbnailPath;
            }

            //// Xử lý ảnh
            if (isset($txtScreenshots) && is_array($txtScreenshots)) {
                $fileCount = count($txtScreenshots['name']);

                for ($i = 0; $i < $fileCount; $i++) {
                    // Kiểm tra nếu file là URL thì lưu vào database
                    if (filter_var($txtScreenshots['full_path'][$i], FILTER_VALIDATE_URL)) {
                        $txtScreenshots['screenshot'][$i] = $txtScreenshots['full_path'][$i];
                    } else {
                        // Lấy tên file và đường dẫn
                        $fileName = $txtScreenshots['name'][$i];

                        // Tạo publicId
                        $publicId = $productId . '_screenshot_' . $i;

                        // Upload file và lưu kết quả
                        $uploadedUrl = uploadFileToCloudinary(
                            $txtScreenshots['tmp_name'][$i],
                            'KTGK/screenshots',
                            'image',
                            $publicId
                        );

                        // Lưu URL vào mảng
                        $txtScreenshots['screenshot'][$i] = $uploadedUrl['url'];
                    }
                }
            }

            // Xử lý video
            if (isset($txtVideos) && is_array($txtVideos)) {
                $fileCount = count($txtVideos['name']);

                for ($i = 0; $i < $fileCount; $i++) {
                    // Kiểm tra nếu file là URL thì lưu vào database
                    if (filter_var($txtVideos['full_path'][$i], FILTER_VALIDATE_URL)) {
                        // Lưu URL vào mảng
                        $txtVideos['video'][$i] = $txtVideos['full_path'][$i];
                        $txtVideos['thumbnail'][$i] = $txtVideos['full_path'][$i];
                    } else {
                        // Lấy tên file và đường dẫn
                        $fileName = $txtVideos['name'][$i];

                        // Tạo publicId
                        $publicId = $productId . '_video_' . $i;

                        // Upload file và lưu kết quả
                        $uploadedFile = uploadFileToCloudinary(
                            $txtVideos['tmp_name'][$i],
                            'KTGK/videos',
                            'video',
                            $publicId
                        );

                        // Tạo thunhumbnail từ video
                        $videoFile = $txtVideos['tmp_name'][$i]; // Đường dẫn file video
                        $thumbnailFile = "uploads/sample_thumbnail.jpg";  // Ảnh thumbnail
                        $thumbnailPath = generateThumbnail($videoFile, $thumbnailFile);

                        $publicId = $productId . '_thumbnail_' . $i;

                        // Upload thumbnail lên Cloudinary
                        $uploadedThumbnail = uploadFileToCloudinary(
                            $thumbnailPath,
                            'KTGK/thumbnails',
                            'image',
                            $publicId
                        );

                        // Lưu URL vào mảng
                        $txtVideos['video'][$i] = $uploadedFile['url'];
                        $txtVideos['thumbnail'][$i] = $uploadedThumbnail['url'];
                    }
                }
            }

            /*===================================================================

                                    Thêm chi tiết sản phẩm

            ===================================================================*/
            $tables = [
                'product_developers',
                'product_publishers',
                'product_platforms',
                'product_genres',
                'product_tags',
                'product_features',
                'product_screenshots',
                'product_videos'
            ];

            $data = [
                'product_developers' => [
                    'developer' => explode(',', $txtDeveloper)
                ],
                'product_publishers' => [
                    'publisher' => explode(',', $txtPublisher)
                ],
                'product_platforms' => [
                    'platform_id' => explode(',', $txtPlatform)
                ],
                'product_genres' => [
                    'genre_id' => explode(',', $txtGenres)
                ],
                'product_tags' => [
                    'tag_id' => explode(',', $txtTags)
                ],
                'product_features' => [
                    'feature_id' => explode(',', $txtFeatures)
                ],
                'product_screenshots' => [
                    'screenshot' => $txtScreenshots['screenshot']
                ],
                'product_videos' => [
                    'mp4' => $txtVideos['video'],
                    'webm' => $txtVideos['video'],
                    'thumbnail' => $txtVideos['thumbnail']
                ]
            ];

            $productController->addProductDetails($tables, $productId, $data);
        }
    }
}
?>

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products', 'Add Product'];
        $pageHeader = 'Add Product';
        include '../../../../components/page-header.php';
        ?>
    </div>

    <form action="" method="post" enctype="multipart/form-data" class="form" id="productAddForm">
        <!-- Title -->
        <div class="form-group">
            <label for="title" class="form-label">
                Title <span class="required">*</span>
            </label>
            <div class="form-control-wrapper">
                <input class="form-control"
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter title"
                    value="<?php echo $txtTitle ?? ''; ?>">

                <fieldset class="form-control-outline <?php echo isset($errors['title']) && $errors['title'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['title']) && $errors['title'] !== '') {
                echo '<span class="error-message">' . $errors['title'] . '</span>';
            }
            ?>
        </div>

        <!-- Type -->
        <div class="form-group form-group-select" id="type">
            <label for="type" class="form-label">
                Type <span class="required">*</span>
            </label>
            <div class="form-control-wrapper select">
                <div tabindex="0"
                    role="combobox"
                    id="type"
                    class="select-box">
                    <div class="select-value">
                        <em style="color: gray;">Select Type</em>
                    </div>
                </div>
                <input class="form-control-select form-control-select-single"
                    id="type"
                    name="type"
                    value="<?php echo $txtType ?? ''; ?>">

                <svg class="select-icon"
                    focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                    <path d="M7 10l5 5 5-5z"></path>
                </svg>
                <fieldset class="form-control-outline <?php echo isset($errors['type']) && $errors['type'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>
            <?php
            if (isset($errors['type']) && $errors['type'] !== '') {
                echo '<span class="error-message">' . $errors['type'] . '</span>';
            }
            ?>

            <!-- Dropdown -->
            <div class="dropdown-select-menu">
                <div class="dropdown-select-list-wrapper">
                    <ul class="dropdown-select-list">
                        <?php foreach ($types as $type) : ?>
                            <li class="dropdown-select-item" data-value="<?php echo $type['id']; ?>">
                                <span class="dropdown-select-item-checkbox-wrapper">
                                    <input
                                        class="dropdown-select-item-checkbox"
                                        data-indeterminate="false"
                                        type="checkbox">

                                    <svg class="dropdown-select-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                    </svg>
                                </span>

                                <div class="dropdown-select-item-text">
                                    <span class="dropdown-select-item-text-primary">
                                        <?php echo $type['name']; ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description" class="form-label">
                Description <span class="required">*</span>
            </label>
            <div class="form-control-wrapper">
                <textarea class="form-control"
                    id="description"
                    name="description"
                    placeholder="Enter description"><?php echo $txtDescription ?? ''; ?></textarea>

                <fieldset class="form-control-outline <?php echo isset($errors['description']) && $errors['description'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['description']) && $errors['description'] !== '') {
                echo '<span class="error-message">' . $errors['description'] . '</span>';
            }
            ?>
        </div>

        <!-- Details -->
        <div class="form-group form-group-quill">
            <label for="details" class="form-label">
                Details
            </label>

            <input class="form-control-quill"
                type="text"
                id="details"
                name="details"
                hidden>

            <div class="form-control-wrapper">
                <!-- Quill -->
                <div id="quill-editor"></div>
                <!-- End Quill -->
            </div>
        </div>

        <!-- Price and Discount -->
        <div class="form-group form-group-flex">
            <!-- Price -->
            <div class="form-group form-group-price">
                <label for="price" class="form-label">
                    Price <span class="required">*</span>
                </label>
                <div class="form-control-wrapper">
                    <input class="form-control"
                        type="number"
                        id="price"
                        name="price"
                        min="0"
                        placeholder="Enter price"
                        value="<?php echo $txtPrice ?? ''; ?>">

                    <fieldset class="form-control-outline <?php echo isset($errors['price']) && $errors['price'] !== '' ? 'error' : ''; ?>"></fieldset>
                </div>

                <?php
                if (isset($errors['price']) && $errors['price'] !== '') {
                    echo '<span class="error-message">' . $errors['price'] . '</span>';
                }
                ?>
            </div>

            <!-- Discount -->
            <div class="form-group form-group-discount">
                <label for="discount" class="form-label">
                    Discount <span class="required">*</span>
                </label>
                <div class="form-control-wrapper">
                    <input class="form-control"
                        type="number"
                        id="discount"
                        name="discount"
                        min="0"
                        max="100"
                        placeholder="Enter discount"
                        value="<?php echo $txtDiscount ?? ''; ?>">

                    <fieldset class="form-control-outline <?php echo isset($errors['discount']) && $errors['discount'] !== '' ? 'error' : ''; ?>"></fieldset>
                </div>

                <?php
                if (isset($errors['discount']) && $errors['discount'] !== '') {
                    echo '<span class="error-message">' . $errors['discount'] . '</span>';
                }
                ?>
            </div>
        </div>

        <!-- Discount Start and End Date -->
        <div class="form-group form-group-flex">
            <!-- Discount Start Date -->
            <div class="form-group
                form-group-discount-start-date">
                <label for="discount-start-date" class="form-label">
                    Discount Start Date
                    <span class="form-label-hint">
                        (Discount > 0)
                    </span>
                </label>
                <div class="form-control-wrapper">
                    <input class="form-control form-control-datetime"
                        type="datetime-local"
                        id="discount-start-date"
                        name="discount-start-date"
                        placeholder="Enter discount start date"
                        value="<?php echo $txtDiscountStartDate ?? ''; ?>">

                    <fieldset class="form-control-outline <?php echo isset($errors['discount-start-date']) && $errors['discount-start-date'] !== '' ? 'error' : ''; ?>"></fieldset>
                </div>

                <?php
                if (isset($errors['discount-start-date']) && $errors['discount-start-date'] !== '') {
                    echo '<span class="error-message">' . $errors['discount-start-date'] . '</span>';
                }
                ?>
            </div>

            <!-- Discount End Date -->
            <div class="form-group
                form-group-discount-end-date">
                <label for="discount-end-date" class="form-label">
                    Discount End Date
                    <span class="form-label-hint">
                        (Discount > 0)
                    </span>
                </label>
                <div class="form-control-wrapper">
                    <input class="form-control form-control-datetime"
                        type="datetime-local"
                        id="discount-end-date"
                        name="discount-end-date"
                        placeholder="Enter discount end date"
                        value="<?php echo $txtDiscountEndDate ?? ''; ?>">

                    <fieldset class="form-control-outline <?php echo isset($errors['discount-end-date']) && $errors['discount-end-date'] !== '' ? 'error' : ''; ?>"></fieldset>
                </div>

                <?php
                if (isset($errors['discount-end-date'])) {
                    echo '<span class="error-message">' . $errors['discount-end-date'] . '</span>';
                }
                ?>
            </div>

            <script>
                // Xử lý sự kiện khi discount < 0
                const discountInput = document.getElementById('discount');
                const discountStartDateInput = document.getElementById('discount-start-date');
                const discountEndDateInput = document.getElementById('discount-end-date');

                // Mặc định disable discount start và end date
                discountStartDateInput.disabled = true;
                discountEndDateInput.disabled = true;

                // Khi discount > 0 thì enable discount start và end date
                if (discountInput.value > 0) {
                    discountStartDateInput.disabled = false;
                    discountEndDateInput.disabled = false;
                }

                // Khi discount < 0 thì disable discount start và end date
                discountInput.addEventListener('input', function() {
                    if (discountInput.value <= 0) {
                        discountStartDateInput.disabled = true;
                        discountEndDateInput.disabled = true;
                    } else {
                        discountStartDateInput.disabled = false;
                        discountEndDateInput.disabled = false;
                    }
                });
            </script>
        </div>

        <!-- Release Date -->
        <div class="form-group">
            <label for="release-date" class="form-label ">
                Release Date <span class="required">*</span>
            </label>
            <div class="form-control-wrapper">
                <input class="form-control form-control-datetime"
                    type="datetime-local"
                    id="release-date"
                    name="release-date"
                    placeholder="Enter release date"
                    value="<?php echo $txtReleaseDate ?? ''; ?>">

                <fieldset class="form-control-outline <?php echo isset($errors['release-date']) && $errors['release-date'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['release-date']) && $errors['release-date'] !== '') {
                echo '<span class="error-message">' . $errors['release-date'] . '</span>';
            }
            ?>
        </div>

        <!-- Developer and Publisher -->
        <div class="form-group form-group-flex">
            <!-- Developer -->
            <div class="form-group form-group-developer">
                <label for="developer" class="form-label">
                    Developer
                </label>
                <div class="form-control-wrapper">
                    <input class="form-control"
                        type="text"
                        id="developer"
                        name="developer"
                        placeholder="Enter developer"
                        value="<?php echo $txtDeveloper ?? ''; ?>">

                    <fieldset class="form-control-outline <?php echo isset($errors['developer']) && $errors['developer'] !== '' ? 'error' : ''; ?>"></fieldset>
                </div>

                <?php
                if (isset($errors['developer']) && $errors['developer'] !== '') {
                    echo '<span class="error-message">' . $errors['developer'] . '</span>';
                }
                ?>
            </div>

            <!-- Publisher -->
            <div class="form-group form-group-publisher">
                <label for="publisher" class="form-label">
                    Publisher
                </label>
                <div class="form-control-wrapper">
                    <input class="form-control"
                        type="text"
                        id="publisher"
                        name="publisher"
                        placeholder="Enter publisher"
                        value="<?php echo $txtPublisher ?? ''; ?>">

                    <fieldset class="form-control-outline <?php echo isset($errors['publisher']) && $errors['publisher'] !== '' ? 'error' : ''; ?>"></fieldset>
                </div>

                <?php
                if (isset($errors['publisher']) && $errors['publisher'] !== '') {
                    echo '<span class="error-message">' . $errors['publisher'] . '</span>';
                }
                ?>
            </div>
        </div>

        <!-- Platform -->
        <div class="form-group form-group-select-multiple" id="platform">
            <label for="platform" class="form-label">
                Platform
            </label>
            <div class="form-control-wrapper select">
                <div tabindex="0"
                    role="combobox"
                    id="platform"
                    data-placeholder="Select Platform"
                    class="select-box">
                    <div class="select-value"><em style="color: gray;">Select Platform</em></div>
                </div>
                <input class="form-control-select form-control-select-multiple"
                    id="platform"
                    name="platform"
                    value="<?php echo $txtPlatform ?? ''; ?>">

                <svg class="select-icon"
                    focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                    <path d="M7 10l5 5 5-5z"></path>
                </svg>

                <fieldset class="form-control-outline <?php echo isset($errors['platform']) && $errors['platform'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['platform']) && $errors['platform'] !== '') {
                echo '<span class="error-message">' . $errors['platform'] . '</span>';
            }
            ?>

            <!-- Dropdown -->
            <div class="dropdown-select-menu">
                <div class="dropdown-select-list-wrapper">
                    <ul class="dropdown-select-list">
                        <?php foreach ($platforms as $platform) : ?>
                            <li class="dropdown-select-item" data-value="<?php echo $platform['id']; ?>">
                                <span class="dropdown-select-item-checkbox-wrapper">
                                    <input
                                        class="dropdown-select-item-checkbox"
                                        data-indeterminate="false"
                                        type="checkbox">

                                    <svg class="dropdown-select-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                    </svg>
                                </span>

                                <div class="dropdown-select-item-text">
                                    <span class="dropdown-select-item-text-primary"><?php echo $platform['name']; ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Genres -->
        <div class="form-group form-group-select-multiple" id="genres">
            <label for="genres" class="form-label">
                Genres
            </label>
            <div class="form-control-wrapper select">
                <div tabindex="0"
                    role="combobox"
                    id="genres"
                    data-placeholder="Select Genres"
                    class="select-box">
                    <div class="select-value"><em style="color: gray;">Select Genres</em></div>
                </div>
                <input class="form-control-select form-control-select-multiple"
                    id="genres"
                    name="genres"
                    value="<?php echo $txtGenres ?? ''; ?>">

                <svg class="select-icon"
                    focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                    <path d="M7 10l5 5 5-5z"></path>
                </svg>

                <fieldset class="form-control-outline <?php echo isset($errors['genres']) && $errors['genres'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['genres']) && $errors['genres'] !== '') {
                echo '<span class="error-message">' . $errors['genres'] . '</span>';
            }
            ?>

            <!-- Dropdown -->
            <div class="dropdown-select-menu">
                <!-- Search -->
                <div class="filter-select-search">
                    <input type="search"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitilize="off"
                        spellcheck="false"
                        placeholder="Search">
                </div>

                <!-- List -->
                <div class="dropdown-select-list-wrapper">
                    <ul class="dropdown-select-list">
                        <?php foreach ($genres as $genre) : ?>
                            <li class="dropdown-select-item" data-value="<?php echo $genre['id']; ?>">
                                <span class="dropdown-select-item-checkbox-wrapper">
                                    <input
                                        class="dropdown-select-item-checkbox"
                                        data-indeterminate="false"
                                        type="checkbox">

                                    <svg class="dropdown-select-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                    </svg>
                                </span>

                                <div class="dropdown-select-item-text">
                                    <span class="dropdown-select-item-text-primary"><?php echo $genre['name']; ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="form-group form-group-select-multiple" id="tags">
            <label for="tags" class="form-label">
                Tags
            </label>
            <div class="form-control-wrapper select">
                <div tabindex="0"
                    role="combobox"
                    id="tags"
                    data-placeholder="Select Tags"
                    class="select-box">
                    <div class="select-value"><em style="color: gray;">Select Tags</em></div>
                </div>
                <input class="form-control-select form-control-select-multiple"
                    id="tags"
                    name="tags"
                    value="<?php echo $txtTags ?? ''; ?>">

                <svg class="select-icon"
                    focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                    <path d="M7 10l5 5 5-5z"></path>
                </svg>

                <fieldset class="form-control-outline <?php echo isset($errors['tags']) && $errors['tags'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['tags']) && $errors['tags'] !== '') {
                echo '<span class="error-message">' . $errors['tags'] . '</span>';
            }
            ?>

            <!-- Dropdown -->
            <div class="dropdown-select-menu">
                <!-- Search -->
                <div class="filter-select-search">
                    <input type="search"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitilize="off"
                        spellcheck="false"
                        placeholder="Search">
                </div>

                <!-- List -->
                <div class="dropdown-select-list-wrapper">
                    <ul class="dropdown-select-list">
                        <?php foreach ($tags as $tag) : ?>
                            <li class="dropdown-select-item" data-value="<?php echo $tag['id']; ?>">
                                <span class="dropdown-select-item-checkbox-wrapper">
                                    <input
                                        class="dropdown-select-item-checkbox"
                                        data-indeterminate="false"
                                        type="checkbox">

                                    <svg class="dropdown-select-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                    </svg>
                                </span>

                                <div class="dropdown-select-item-text">
                                    <span class="dropdown-select-item-text-primary"><?php echo $tag['name']; ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="form-group form-group-select-multiple" id="features">
            <label for="features" class="form-label">
                Features
            </label>
            <div class="form-control-wrapper select">
                <div tabindex="0"
                    role="combobox"
                    id="features"
                    data-placeholder="Select Features"
                    class="select-box">
                    <div class="select-value"><em style="color: gray;">Select Features</em></div>
                </div>
                <input class="form-control-select form-control-select-multiple"
                    id="features"
                    name="features"
                    value="<?php echo $txtFeatures ?? ''; ?>">

                <svg class="select-icon"
                    focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                    <path d="M7 10l5 5 5-5z"></path>
                </svg>

                <fieldset class="form-control-outline <?php echo isset($errors['features']) && $errors['features'] !== '' ? 'error' : ''; ?>"></fieldset>
            </div>

            <?php
            if (isset($errors['features']) && $errors['features'] !== '') {
                echo '<span class="error-message">' . $errors['features'] . '</span>';
            }
            ?>

            <!-- Dropdown -->
            <div class="dropdown-select-menu">
                <!-- Search -->
                <div class="filter-select-search">
                    <input type="search"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitilize="off"
                        spellcheck="false"
                        placeholder="Search">
                </div>

                <!-- List -->
                <div class="dropdown-select-list-wrapper">
                    <ul class="dropdown-select-list">
                        <?php foreach ($features as $feature) : ?>
                            <li class="dropdown-select-item" data-value="<?php echo $feature['id']; ?>">
                                <span class="dropdown-select-item-checkbox-wrapper">
                                    <input
                                        class="dropdown-select-item-checkbox"
                                        data-indeterminate="false"
                                        type="checkbox">

                                    <svg class="dropdown-select-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                    </svg>
                                </span>

                                <div class="dropdown-select-item-text">
                                    <span class="dropdown-select-item-text-primary"><?php echo $feature['name']; ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Header image -->
        <div class="form-group form-group-file">
            <div class="form-group-header">
                <label for="header-image" class="form-label">
                    Header Image
                </label>

                <!-- Add image from url -->
                <button class="form-control-url-btn" type="button" id="addImageFromUrl">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                        <path d="M280 341.1l-1.2.1c-3.6.4-7 2-9.6 4.5l-64.6 64.6c-13.7 13.7-32 21.2-51.5 21.2s-37.8-7.5-51.5-21.2c-13.7-13.7-21.2-32-21.2-51.5s7.5-37.8 21.2-51.5l68.6-68.6c3.5-3.5 7.3-6.6 11.4-9.3 4.6-3 9.6-5.6 14.8-7.5 4.8-1.8 9.9-3 15-3.7 3.4-.5 6.9-.7 10.2-.7 1.4 0 2.8.1 4.6.2 17.7 1.1 34.4 8.6 46.8 21 7.7 7.7 13.6 17.1 17.1 27.3 2.8 8 11.2 12.5 19.3 10.1.1 0 .2-.1.3-.1.1 0 .2 0 .2-.1 8.1-2.5 12.8-11 10.5-19.1-4.4-15.6-12.2-28.7-24.6-41-15.6-15.6-35.9-25.8-57.6-29.3-1.9-.3-3.8-.6-5.7-.8-3.7-.4-7.4-.6-11.1-.6-2.6 0-5.2.1-7.7.3-5.4.4-10.8 1.2-16.2 2.5-1.1.2-2.1.5-3.2.8-6.7 1.8-13.3 4.2-19.5 7.3-10.3 5.1-19.6 11.7-27.7 19.9l-68.6 68.6C58.9 304.4 48 330.8 48 359c0 28.2 10.9 54.6 30.7 74.4C98.5 453.1 124.9 464 153 464c28.2 0 54.6-10.9 74.4-30.7l65.3-65.3c10.4-10.5 2-28.3-12.7-26.9z"></path>
                        <path d="M433.3 78.7C413.5 58.9 387.1 48 359 48s-54.6 10.9-74.4 30.7l-63.7 63.7c-9.7 9.7-3.6 26.3 10.1 27.4 4.7.4 9.3-1.3 12.7-4.6l63.8-63.6c13.7-13.7 32-21.2 51.5-21.2s37.8 7.5 51.5 21.2c13.7 13.7 21.2 32 21.2 51.5s-7.5 37.8-21.2 51.5l-68.6 68.6c-3.5 3.5-7.3 6.6-11.4 9.3-4.6 3-9.6 5.6-14.8 7.5-4.8 1.8-9.9 3-15 3.7-3.4.5-6.9.7-10.2.7-1.4 0-2.9-.1-4.6-.2-17.7-1.1-34.4-8.6-46.8-21-7.3-7.3-12.8-16-16.4-25.5-2.9-7.7-11.1-11.9-19.1-9.8-8.9 2.3-14.1 11.7-11.3 20.5 4.5 14 12.1 25.9 23.7 37.5l.2.2c16.9 16.9 39.4 27.6 63.3 30.1 3.7.4 7.4.6 11.1.6 2.6 0 5.2-.1 7.8-.3 6.5-.5 13-1.6 19.3-3.2 6.7-1.8 13.3-4.2 19.5-7.3 10.3-5.1 19.6-11.7 27.7-19.9l68.6-68.6c19.8-19.8 30.7-46.2 30.7-74.4s-11.1-54.6-30.9-74.4z"></path>
                    </svg>
                    Add image from URL
                </button>
            </div>

            <!-- Add image from url -->
            <div class="form-control-wrapper form-control-wrapper-file <?php echo isset($errors['headerImage']) && $errors['headerImage'] !== '' ? 'error' : ''; ?>">
                <!-- Uploader -->
                <div class="file-uploader-container">
                    <!-- Input -->
                    <input class="form-control-file"
                        type="file"
                        id="headerImage"
                        name="headerImage"
                        accept="image/*">

                    <!-- Label -->
                    <label for="headerImage" class="file-uploader">
                        <!-- Icon -->
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>

                        <!-- Text -->
                        <span class="file-uploader-text">
                            Choose a file…
                        </span>
                    </label>
                </div>

                <!-- Preview -->
                <div class="file-preview" style="display: none;">
                    <!-- Image -->
                    <img src=""
                        alt="Header Image"
                        class="file-preview-image">

                    <!-- Info -->
                    <div class="file-preview-info">
                        <!-- Name -->
                        <p class="file-preview-name"></p>

                        <!-- Size -->
                        <p class="file-preview-size"></p>
                    </div>

                    <!-- Button -->
                    <button class="file-preview-remove" type="button">
                        <svg class="file-preview-remove-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="DeleteIcon">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <?php
            if (isset($errors['headerImage']) && $errors['headerImage'] !== '') {
                echo '<span class="error-message">' . $errors['headerImage'] . '</span>';
            }
            ?>
        </div>

        <!-- Screenshots -->
        <div class="form-group form-group-dropzone">
            <div class="form-group-header">
                <label for="screenshots" class="form-label">
                    Screenshots
                </label>

                <!-- Add image from url -->
                <button class="form-control-url-btn" type="button" id="addImageDropzoneFromUrl">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                        <path d="M280 341.1l-1.2.1c-3.6.4-7 2-9.6 4.5l-64.6 64.6c-13.7 13.7-32 21.2-51.5 21.2s-37.8-7.5-51.5-21.2c-13.7-13.7-21.2-32-21.2-51.5s7.5-37.8 21.2-51.5l68.6-68.6c3.5-3.5 7.3-6.6 11.4-9.3 4.6-3 9.6-5.6 14.8-7.5 4.8-1.8 9.9-3 15-3.7 3.4-.5 6.9-.7 10.2-.7 1.4 0 2.8.1 4.6.2 17.7 1.1 34.4 8.6 46.8 21 7.7 7.7 13.6 17.1 17.1 27.3 2.8 8 11.2 12.5 19.3 10.1.1 0 .2-.1.3-.1.1 0 .2 0 .2-.1 8.1-2.5 12.8-11 10.5-19.1-4.4-15.6-12.2-28.7-24.6-41-15.6-15.6-35.9-25.8-57.6-29.3-1.9-.3-3.8-.6-5.7-.8-3.7-.4-7.4-.6-11.1-.6-2.6 0-5.2.1-7.7.3-5.4.4-10.8 1.2-16.2 2.5-1.1.2-2.1.5-3.2.8-6.7 1.8-13.3 4.2-19.5 7.3-10.3 5.1-19.6 11.7-27.7 19.9l-68.6 68.6C58.9 304.4 48 330.8 48 359c0 28.2 10.9 54.6 30.7 74.4C98.5 453.1 124.9 464 153 464c28.2 0 54.6-10.9 74.4-30.7l65.3-65.3c10.4-10.5 2-28.3-12.7-26.9z"></path>
                        <path d="M433.3 78.7C413.5 58.9 387.1 48 359 48s-54.6 10.9-74.4 30.7l-63.7 63.7c-9.7 9.7-3.6 26.3 10.1 27.4 4.7.4 9.3-1.3 12.7-4.6l63.8-63.6c13.7-13.7 32-21.2 51.5-21.2s37.8 7.5 51.5 21.2c13.7 13.7 21.2 32 21.2 51.5s-7.5 37.8-21.2 51.5l-68.6 68.6c-3.5 3.5-7.3 6.6-11.4 9.3-4.6 3-9.6 5.6-14.8 7.5-4.8 1.8-9.9 3-15 3.7-3.4.5-6.9.7-10.2.7-1.4 0-2.9-.1-4.6-.2-17.7-1.1-34.4-8.6-46.8-21-7.3-7.3-12.8-16-16.4-25.5-2.9-7.7-11.1-11.9-19.1-9.8-8.9 2.3-14.1 11.7-11.3 20.5 4.5 14 12.1 25.9 23.7 37.5l.2.2c16.9 16.9 39.4 27.6 63.3 30.1 3.7.4 7.4.6 11.1.6 2.6 0 5.2-.1 7.8-.3 6.5-.5 13-1.6 19.3-3.2 6.7-1.8 13.3-4.2 19.5-7.3 10.3-5.1 19.6-11.7 27.7-19.9l68.6-68.6c19.8-19.8 30.7-46.2 30.7-74.4s-11.1-54.6-30.9-74.4z"></path>
                    </svg>
                    Add image from URL
                </button>
            </div>

            <input class="form-control-dropzone"
                type="file"
                id="screenshots"
                name="screenshots[]"
                accept="image/*"
                multiple
                style="display: none;">

            <!-- Dropzone -->
            <div id="addScreenshots"
                class="js-dropzone dropzone-custom <?php echo isset($errors['screenshots']) && $errors['screenshots'] !== '' ? 'error' : ''; ?>"
                data-type-file="image">
                <div class="dropzone-custom-wrapper dz-message">
                    <img class="dropzone-custom-icon" src="/admin/assets/icon/browse.svg" alt="Image Description">

                    <h5>Drag and drop your file here</h5>

                    <p>or</p>

                    <span class="dropzone-custom-btn">Browse files</span>
                </div>
            </div>

            <?php
            if (isset($errors['screenshots']) && $errors['screenshots'] !== '') {
                echo '<span class="error-message">' . $errors['screenshots'] . '</span>';
            }
            ?>
        </div>

        <!-- Videos -->
        <div class="form-group form-group-dropzone">
            <div class="form-group-header">
                <label for="videos" class="form-label">
                    Videos
                </label>

                <!-- Add image from url -->
                <button class="form-control-url-btn" type="button" id="addImageDropzoneFromUrl">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                        <path d="M280 341.1l-1.2.1c-3.6.4-7 2-9.6 4.5l-64.6 64.6c-13.7 13.7-32 21.2-51.5 21.2s-37.8-7.5-51.5-21.2c-13.7-13.7-21.2-32-21.2-51.5s7.5-37.8 21.2-51.5l68.6-68.6c3.5-3.5 7.3-6.6 11.4-9.3 4.6-3 9.6-5.6 14.8-7.5 4.8-1.8 9.9-3 15-3.7 3.4-.5 6.9-.7 10.2-.7 1.4 0 2.8.1 4.6.2 17.7 1.1 34.4 8.6 46.8 21 7.7 7.7 13.6 17.1 17.1 27.3 2.8 8 11.2 12.5 19.3 10.1.1 0 .2-.1.3-.1.1 0 .2 0 .2-.1 8.1-2.5 12.8-11 10.5-19.1-4.4-15.6-12.2-28.7-24.6-41-15.6-15.6-35.9-25.8-57.6-29.3-1.9-.3-3.8-.6-5.7-.8-3.7-.4-7.4-.6-11.1-.6-2.6 0-5.2.1-7.7.3-5.4.4-10.8 1.2-16.2 2.5-1.1.2-2.1.5-3.2.8-6.7 1.8-13.3 4.2-19.5 7.3-10.3 5.1-19.6 11.7-27.7 19.9l-68.6 68.6C58.9 304.4 48 330.8 48 359c0 28.2 10.9 54.6 30.7 74.4C98.5 453.1 124.9 464 153 464c28.2 0 54.6-10.9 74.4-30.7l65.3-65.3c10.4-10.5 2-28.3-12.7-26.9z"></path>
                        <path d="M433.3 78.7C413.5 58.9 387.1 48 359 48s-54.6 10.9-74.4 30.7l-63.7 63.7c-9.7 9.7-3.6 26.3 10.1 27.4 4.7.4 9.3-1.3 12.7-4.6l63.8-63.6c13.7-13.7 32-21.2 51.5-21.2s37.8 7.5 51.5 21.2c13.7 13.7 21.2 32 21.2 51.5s-7.5 37.8-21.2 51.5l-68.6 68.6c-3.5 3.5-7.3 6.6-11.4 9.3-4.6 3-9.6 5.6-14.8 7.5-4.8 1.8-9.9 3-15 3.7-3.4.5-6.9.7-10.2.7-1.4 0-2.9-.1-4.6-.2-17.7-1.1-34.4-8.6-46.8-21-7.3-7.3-12.8-16-16.4-25.5-2.9-7.7-11.1-11.9-19.1-9.8-8.9 2.3-14.1 11.7-11.3 20.5 4.5 14 12.1 25.9 23.7 37.5l.2.2c16.9 16.9 39.4 27.6 63.3 30.1 3.7.4 7.4.6 11.1.6 2.6 0 5.2-.1 7.8-.3 6.5-.5 13-1.6 19.3-3.2 6.7-1.8 13.3-4.2 19.5-7.3 10.3-5.1 19.6-11.7 27.7-19.9l68.6-68.6c19.8-19.8 30.7-46.2 30.7-74.4s-11.1-54.6-30.9-74.4z"></path>
                    </svg>
                    Add video from URL
                </button>
            </div>

            <input class="form-control-dropzone"
                type="file"
                id="videos"
                name="videos[]"
                accept="video/*"
                multiple
                style="display: none;">

            <!-- Dropzone -->
            <div id="addVideos"
                class="js-dropzone dropzone-custom <?php echo isset($errors['videos']) && $errors['videos'] !== '' ? 'error' : ''; ?>"
                data-type-file="video">
                <div class="dropzone-custom-wrapper dz-message">
                    <img class="dropzone-custom-icon" src="/admin/assets/icon/browse.svg" alt="Image Description">

                    <h5>Drag and drop your file here</h5>

                    <p>or</p>

                    <span class="dropzone-custom-btn">Browse files</span>
                </div>
            </div>

            <?php
            if (isset($errors['videos']) && $errors['videos'] !== '') {
                echo '<span class="error-message">' . $errors['videos'] . '</span>';
            }
            ?>
        </div>

        <!-- Is active -->
        <label class="form-group form-group-checkbox" for="isActive">
            <div class="form-control-wrapper">
                <input class="form-control-checkbox"
                    type="checkbox"
                    id="isActive"
                    name="isActive"
                    value="1">

                <svg class="checkbox-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CheckBoxOutlineBlankIcon">
                    <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                </svg>
            </div>
            <span class="form-label">
                Active
            </span>
        </label>

        <!-- Submit -->
        <div class="form-group form-group-submit">
            <button type="submit" class="submit-btn">Add Product</button>
        </div>
    </form>
</article>

<script src="/components/popupInput.js"></script>
<script src="/assets/js/quill.js"></script>
<script src="/assets/js/dropzone.js"></script>
<script src="product-add.js"></script>

<?php
// Lấy nội dung và lưu vào biến $content
$content = ob_get_clean();

// Nạp layout chính
include '../../../../layout.php';

// Đóng kết nối
DongKetNoi($conn);
