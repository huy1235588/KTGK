<?php
include 'utils/db_connect.php';
session_start();
$conn = MoKetNoi();

// Lấy ra 2 sản phẩm mới nhất
$sql = "SELECT * FROM products ORDER BY releaseDate DESC LIMIT 6";
$result = mysqli_query($conn, $sql);
$latestProducts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ha - Premium Gaming Experience</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #0a0a0a;
            color: white;
        }

        /* Header Styles */
        header {
            background-color: rgba(0, 0, 0, 0.9);
            padding: 1rem 5%;
            position: fixed;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            color: #00ff88;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #00ff88;
        }

        /* Hero Section with Parallax Effect */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1538481199705-c710c4e965fc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            /* Parallax effect */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 5%;
        }

        .hero-content h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .cta-btn {
            padding: 1rem 2rem;
            background-color: #00ff88;
            color: #0a0a0a;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .cta-btn:hover {
            transform: scale(1.05);
        }

        /* Features Section */
        .features {
            padding: 5rem 5%;
            background-color: #111;
        }

        .section-title {
            display: flex;
            justify-content: center;
            gap: 1rem;
            align-items: center;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            background-color: #1a1a1a;
            border-radius: 10px;
            transition: transform 0.3s, opacity 0.6s;
            opacity: 0;
            transform: translateY(20px);
        }

        .feature-card.active {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card i {
            font-size: 2.5rem;
            color: #00ff88;
            margin-bottom: 1rem;
        }

        /* About Us Section */
        .about-us {
            padding: 5rem 5%;
            background-color: #111;
            text-align: center;
        }

        .about-us p {
            font-size: 1.2rem;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Latest Releases Section */
        .latest-games {
            padding: 5rem 5%;
            background-color: #1a1a1a;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .game-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, opacity 0.6s;
            opacity: 0;
            transform: translateY(20px);
        }

        .game-card.active {
            opacity: 1;
            transform: translateY(0);
        }

        .game-card img {
            width: 100%;
            object-fit: cover;
        }

        .game-info {
            padding: 1.5rem;
        }

        .game-info h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: rgb(255, 0, 0);
            /* height: 55px; */

            /* Tối da 2 dòng */
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .game-info .description {
            font-size: 1rem;
            margin-bottom: 1rem;
            height: 80px;
            color:rgb(133, 133, 133);

            /* Tối da 4 dòng */
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price {
            color: #00ff88;
            font-size: 1.2rem;
            margin: 1rem 0;
        }

        .buy-btn {
            background-color: #00ff88;
            color: #0a0a0a;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buy-btn:hover {
            background-color: #00cc77;
        }

        /* Scroll-to-Top Button */
        #scrollToTop {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #00ff88;
            color: #0a0a0a;
            border: none;
            border-radius: 50%;
            padding: 1rem;
            cursor: pointer;
            display: none;
            z-index: 1001;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Next Level Gaming Experience</h1>
            <p>Discover thousands of games across all genres at unbeatable prices</p>
            <button class="cta-btn">Explore Games</button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Why Choose Us</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-rocket"></i>
                <h3>Instant Delivery</h3>
                <p>Get your games immediately after purchase</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure Payments</h3>
                <p>100% secure payment processing</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headset"></i>
                <h3>24/7 Support</h3>
                <p>Round-the-clock customer service</p>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-us" id="about">
        <h2 class="section-title">
            About
            <img class="logo" src="assets/logo1.png" alt="" loading="lazy" />

        </h2>
        <p>
            At Ha, we are passionate about delivering the ultimate gaming experience.
            Our platform is dedicated to providing gamers with a wide selection of titles, unbeatable prices,
            and top-notch customer support.
        </p>
    </section>

    <!-- Latest Releases Section -->
    <section class="latest-games" id="latest-games">
        <h2 class="section-title">Latest Releases</h2>
        <div class="games-grid">
            <?php foreach ($latestProducts as $product) : ?>
                <div class="game-card">
                    <img src="<?= $product['headerImage'] ?>" alt="<?= $product['title'] ?>">
                    <div class="game-info">
                        <h3><?= $product['title'] ?></h3>
                        <p class="description"><?= $product['description'] ?></p>
                        <div class="price">$<?= $product['price'] ?></div>
                        <button class="buy-btn">Buy Now</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Scroll-to-Top Button -->
    <button id="scrollToTop"><i class="fas fa-arrow-up"></i></button>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Reveal animations on scroll for feature and game cards
        window.addEventListener('scroll', reveal);

        function reveal() {
            var reveals = document.querySelectorAll('.feature-card, .game-card');
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var revealTop = reveals[i].getBoundingClientRect().top;
                var revealPoint = 150;

                if (revealTop < windowHeight - revealPoint) {
                    reveals[i].classList.add('active');
                }
            }
        }

        // Scroll-to-top button functionality
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });

        document.getElementById('scrollToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Initialize reveal on page load
        reveal();
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>