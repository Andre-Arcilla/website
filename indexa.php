<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Supplies Online</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="navbar">
                <img class="header-logo" src="images\DCT no bg v2.png">
                <nav class="navigation">
                    <a href="andre/signup.php">Home</a>
                    <a href="#">Products</a>
                    <a href="#">About Us</a>
                    <a href="#">Contact Us</a>
                </nav>
                <div class="search-bar">
                    <input type="text" placeholder="Search Medical Supplies...">
                    <button>Search</button>
                </div>
            </div>
            <nav class="account-info">
                <?php if (isset($_SESSION["userid"])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="andre/login.php">Login</a>
                    <a href="andre/signup.php">Signup</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="hero">
        <div class="hero-container">
            <img src="images\DCT no bg.png">
            <h1>Find the Best Medical Supplies</h1>
            <p>Your one-stop shop for quality medical supplies.</p>
        </div>
    </div>
    <div class="featured-products">
        <h2>Medical Supplies</h2>
        <div class="product-grid">
            <div class="product">
                <img src="images/products/tissue.png" alt="Product 1">
                <h3>Product 1</h3>
                <p>Description of Product 1</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/facemask.png" alt="Product 2">
                <h3>Product 2</h3>
                <p>Description of Product 2</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/headcaps.png" alt="Product 3">
                <h3>Product 3</h3>
                <p>Description of Product 3</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/shoe cover.png" alt="Product 4">
                <h3>Product 4</h3>
                <p>Description of Product 4</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/underpads.png" alt="Product 5">
                <h3>Product 5</h3>
                <p>Description of Product 5</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/gloves.png" alt="Product 6">
                <h3>Product 6</h3>
                <p>Description of Product 6</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/cotton applicator.png" alt="Product 7">
                <h3>Product 7</h3>
                <p>Description of Product 7</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/stethoscope.png" alt="Product 8">
                <h3>Product 8</h3>
                <p>Description of Product 8</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/wrist splint.png" alt="Product 9">
                <h3>Product 9</h3>
                <p>Description of Product 9</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <div class="product">
                <img src="images/products/Traction Splints.png" alt="Product 9">
                <h3>Product 9</h3>
                <p>Description of Product 9</p>
                <a href="#" class="product-button">View Details</a>
            </div>
            <!-- Add more product divs as needed -->
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="footer-nav">
                <a href="#">Home</a>
                <a href="#">Products</a>
                <a href="#">About Us</a>
                <a href="#">Contact Us</a>
            </div>
            <div class="social-icons">
                <!-- Add social media icons here -->
            </div>
        </div>
    </footer>
</body>
</html>
