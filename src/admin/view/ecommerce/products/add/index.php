<?php
// Tiêu đề của trang
$title = "Add Product";
// Link phụ để hiển thị active
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Add Product'];

ob_start();
?>
<link rel="stylesheet" href="product-add.css">

<?php
// Nạp file ProductController.php
require_once '../../../../../controller/ProductController.php';
require_once '../../../../../utils/db_connect.php';

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
    'description' => '',
    'type' => '',
    'price' => '',
    'discount' => '',
    'release-date' => '',
];

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $txtTitle = htmlspecialchars($_POST['title']);
    $txtType = htmlspecialchars($_POST['type']);
    $txtDescription = htmlspecialchars($_POST['description']);
    $txtPrice = htmlspecialchars($_POST['price']);
    $txtDiscount = htmlspecialchars($_POST['discount']);

    // Nếu discount > 0 thì lấy discount start và end date
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
    $txtIsActive = isset($_POST['isActive']) ? 1 : 0;

    // Nếu discount < 0 thì discountStart và discountEnd phải bằng null
    if ($txtDiscount < 0) {
        $txtDiscountStartDate = null;
        $txtDiscountEndDate = null;
    }

    // Kiểm tra dữ liệu không được để trống
    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $errors[$key] = 'This field is required';
        }
    }

    echo 'Title: ' . $txtTitle . '<br/>';
    echo 'Type:' . $txtType . '<br/>';
    echo 'Description: ' . $txtDescription . '<br/>';
    echo 'Price: ' . $txtPrice . '<br/>';
    echo 'Discount: ' . $txtDiscount . '<br/>';
    echo 'Discount Start Date: ' . $txtDiscountStartDate . '<br/>';
    echo 'Discount End Date: ' . $txtDiscountEndDate . '<br/>';
    echo 'Release Date: ' . $txtReleaseDate . '<br/>';
    echo 'Developer: ' . $txtDeveloper . '<br/>';
    echo 'Publisher: ' . $txtPublisher . '<br/>';
    echo 'Platform: ' . $txtPlatform . '<br/>';
    echo 'Genres: ' . $txtGenres . '<br/>';
    echo 'Tags: ' . $txtTags . '<br/>';
    echo 'Features: ' . $txtFeatures . '<br/>';
    echo 'Is Active: ' . $txtIsActive . '<br/>';
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
                            <li class="dropdown-select-item">
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
            <div class="form-group
                form-group-developer">
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

                    <fieldset class="form-control-outline"></fieldset>
                </div>
            </div>

            <!-- Publisher -->
            <div class="form-group
                form-group-publisher">
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

                    <fieldset class="form-control-outline"></fieldset>
                </div>
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

                <fieldset class="form-control-outline"></fieldset>
            </div>

            <!-- Dropdown -->
            <div class="dropdown-select-menu">
                <div class="dropdown-select-list-wrapper">
                    <ul class="dropdown-select-list">
                        <?php foreach ($platforms as $platform) : ?>
                            <li class="dropdown-select-item">
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

                <fieldset class="form-control-outline"></fieldset>
            </div>

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
                            <li class="dropdown-select-item">
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

                <fieldset class="form-control-outline"></fieldset>
            </div>

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
                            <li class="dropdown-select-item">
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

                <fieldset class="form-control-outline"></fieldset>
            </div>

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
                            <li class="dropdown-select-item">
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

<script src="product-add.js"></script>

<?php
// Lấy nội dung và lưu vào biến $content
$content = ob_get_clean();

// Nạp layout chính
include '../../../../layout.php';

// Đóng kết nối
DongKetNoi($conn);
