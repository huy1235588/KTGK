<?php
$title = 'Product Details';
$activeSidebarLink = ['Pages', 'E-commerce', 'Products', 'Product Details'];

ob_start();

$productId = $_GET['id'];
?>

<link rel="stylesheet" href="product-details.css">

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'E-commerce', 'Products', 'Product Details'];
        $pageHeader = 'Product Details';
        include '../../../../components/page-header.php';
        ?>

        <!-- Điều hướng sản phẩm tiếp theo hoặc trước đó -->
        <div class="product-navigation">
            <a href="/admin/view/ecommerce/products/product-details/?id=<?php echo $productId - 1 ?>"
                class="product-navigation-link"
                data-tooltip="Previous Product"
                data-placement="top">

                <!-- Icon -->
                <img src="/admin/assets/icon/arrow1-left.svg" alt="Previous Product">
            </a>
            <a href="/admin/view/ecommerce/products/product-details/?id=<?php echo $productId + 1 ?>"
                class="product-navigation-link"
                data-tooltip="Next Product"
                data-placement="top">

                <!-- Icon -->
                <img src="/admin/assets/icon/arrow1-right.svg" alt="Next Product">
            </a>
        </div>
    </div>

    <div class="product-details-container">

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productId = <?php echo $productId ?>;
            const productDetailsContainer = document.querySelector('.product-details-container');
            const url = `/api/products.php?id=${productId}`;

            fetch(url)
                .then(response => response.json())
                .then(product => {
                    productDetailsContainer.innerHTML = `
                        <div class="product-details">
                            <div class="product-image">
                                <img src="${product.headerImage}" alt="Product Image" loading="lazy">
                            </div>
                            <div class="product-info">
                                <h1 class="product-title">${product.title}</h1>
                                <p class="product-price">$${parseFloat(product.price).toFixed(2)}</p>
                                <p class="product-description">${product.description}</p>
                                <button class="add-to-cart-button">Add to cart</button>
                            </div>
                        </div>
                    `;

                    const addToCartButton = document.querySelector('.add-to-cart-button');
                    addToCartButton.addEventListener('click', () => {
                        const url = 'http://localhost:8080/api/cart.php';
                        const formData = new FormData();
                        formData.append('productId', productId);

                        fetch(url, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                            });
                    });
                });
        });
    </script>

    <script src="/components/tooltip.js"></script>
</article>


<?php
$content = ob_get_clean();
include '../../../../layout.php';

DongKetNoi($conn);
