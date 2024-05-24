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

    // SQL query to fetch top 3 items with highest soldAmount
    $sql = "SELECT itemID, itemPrice, itemStock, soldAmount, itemName FROM items ORDER BY soldAmount DESC LIMIT 3";

    // Execute the query
    $result = $conn->query($sql);

    // Initialize variables to store item data
    $topItems = [];

    // Check if there are any rows returned
    while ($row = $result->fetch_assoc()) {
        $topItems[] = [
            'itemID' => $row["itemID"],
            'itemPrice' => $row["itemPrice"],
            'itemStock' => $row["itemStock"],
            'soldAmount' => $row["soldAmount"],
            'itemName' => $row["itemName"]
        ];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Supplies Online</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="navbar">
                <img id="header-logo" class="header-logo" src="images/DCT no bg v2.png" alt="Logo">
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
    
    <div class="hero">
        <div class="hero-container">
            <img src="images/DCT no bg.png" alt="Hero Image">
            <h1>Find the Best Medical Supplies</h1>
            <b>Your one-stop shop for quality medical supplies.</b>
            <?php
                if (isset($_GET["success"]) && $_GET["success"] == 1) {
                    echo "<hr><p class='success-message'>Order placed successfully!</p>";
                }
            ?>
        </div>
    </div>

    <div class="featured-products">
        <h2>Best Sellers</h2>
        <div class="product-grid">
            <?php foreach ($topItems as $item): ?>
                <div class="product" onclick="toggleProduct(this, 0)">
                    <div>
                        <img src="images/products/<?php echo $item['itemName']; ?>.png" alt="Product">
                        <h3><?php echo strtoupper($item['itemName']); ?></h3>
                    </div>
                    <div class="description">
                        <h3>
                            <h2><?php echo $item['soldAmount']; ?> BOXES SOLD!</h2>
                            <br>
                            Price per box: PHP<?php echo $item['itemPrice']; ?>
                            <br>
                            Stock: <?php echo $item['itemStock']; ?> boxes
                        </h3>
                    </div>
                </div>
            <?php endforeach; ?>
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

    <!-- Hidden GIF image -->
    <img id="hidden-gif" src="images\arisbm.gif">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const products = document.querySelectorAll(".product");

            products.forEach(product => {
                product.addEventListener("click", function() {
                    this.classList.toggle("expanded");

                    const description = this.querySelector(".description");
                    if (description) {
                        description.style.display = this.classList.contains("expanded") ? "block" : "none";
                    }

                    products.forEach(p => {
                        if (p !== this && p.classList.contains("expanded")) {
                            p.classList.remove("expanded");
                            const otherDescription = p.querySelector(".description");
                            if (otherDescription) {
                                otherDescription.style.display = "none";
                            }
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', (event) => {
            const logo = document.getElementById('header-logo');
            const hiddenGif = document.getElementById('hidden-gif');

            if (!logo) {
                console.error('Logo element not found!');
                return;
            }

            let clickCount = 0;
            const maxClicks = 5;
            const clickTimeout = 1000; // 1 second
            let clickTimer = null;

            logo.addEventListener('click', () => {
                clickCount++;
                console.log(`Logo clicked ${clickCount} times`);

                if (clickTimer) {
                    clearTimeout(clickTimer);
                }

                clickTimer = setTimeout(() => {
                    clickCount = 0;
                }, clickTimeout);

                if (clickCount === maxClicks) {
                    hiddenGif.style.display = 'block';
                    clickCount = 0;

                    // Hide the GIF after 3 seconds (3000 ms)
                    setTimeout(() => {
                        hiddenGif.style.display = 'none';
                    }, 3000);
                }
            });
        });
    </script>
</body>
</html>

<?php
    $conn->close();
?>
