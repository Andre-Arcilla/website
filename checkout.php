<?php
    session_start();

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "delta";

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Supplies Online</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="navbar">
                <img class="header-logo" src="images\DCT no bg v2.png">
                <nav class="navigation">
                    <?php if (isset($_SESSION["userid"])): ?>
                        <a href="indexa.php">Home</a>
                        <a href="products.php">Products</a>
                        <a href="#">About Us</a>
                        <a href="#">Contact Us</a>
                <?php else: ?>
                        <a href="login.php">Home</a>
                        <a href="login.php">Products</a>
                        <a href="login.php">About Us</a>
                        <a href="login.php">Contact Us</a>
                <?php endif; ?>
                </nav>
                <div class="search-bar">
                    <input type="text" placeholder="Search Medical Supplies...">
                    <button>Search</button>
                </div>
            </div>
            <nav class="account-info">
                <?php if (isset($_SESSION["userid"])): ?>
                    <a href="actions/logout-action.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Signup</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="contents">
        <form class="form-box" action="actions/checkout-action.php" method="post">
            <div class="qr-box">
                <div class="qr-code">
                    <img src="images\qrcode.png" class="qr">
                    <h1>SCAN THIS QR CODE</h1>
                </div>
                <div class="payment-info">
                    <label>
                        <h2>GCash Account Name:</h2>
                        <input type="text" name="gcashName" required>
                    </label>
                    <label>
                        <h2>GCash Account Number:</h2>
                        <input type="text" name="gcashNumber" required>
                    </label>
                    <label>
                        <h2>Reference Number:</h2>
                        <input type="text" name="gcashReferenceNum" required>
                    </label>
                </div>
            </div>
            <button type="submit" name="checkout">Checkout</button>
        </form>
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