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
                    <a href="indexa.php">Home</a>
                    <a href="products.php">Store</a>
                    <a href="orders.php">Your Orders</a>
                </nav>
            </div>
            <nav class="account-info">
                <?php if (isset($_SESSION["usertype"]) && $_SESSION["usertype"] == 'admin'): ?>
                    <a href="admin pages/adminmain.php">Admin Page</a>
                <?php endif; ?>
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
                <a href="#">Store</a>
            </div>
            <img class="easter-egg" src="images\arisbm.gif">
        </div>
    </footer>
</body>
</html>