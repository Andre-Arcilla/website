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
                    <button class="sidebar-button" onclick="location.href='index.php';">Home</button>
                    <button class="sidebar-button" onclick="location.href='products.php';">Store</button>
                    <button class="sidebar-button" onclick="location.href='orders.php';">Your Orders</button>
                </nav>
            </div>
            
            <?php if (isset($_SESSION["userid"])): ?>
                <div>
                    <b>Hello <?php echo $_SESSION["name"]; ?> !</b>
                </div>
            <?php endif; ?>

            <nav class="account-info">
                <?php if (isset($_SESSION["usertype"]) && $_SESSION["usertype"] == 'admin'): ?>
                    <button class="sidebar-button" onclick="location.href='admin pages/adminIndex.php';">Admin Page</button>
                <?php endif; ?>
                <?php if (isset($_SESSION["userid"])): ?>
                    <button class="sidebar-button" onclick="location.href='actions/logout-action.php';">Logout</button>
                <?php else: ?>
                    <button class="sidebar-button" onclick="location.href='login.php';">Login</button>
                    <button class="sidebar-button" onclick="location.href='signup.php';">Signup</button>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <div class="hero">
        <div class="hero-container">
            <img src="images/DCT no bg.png" alt="Hero Image">
            <h3>Your one-stop shop for quality medical supplies.</h3>
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
                            <h2><?php echo number_format($item['soldAmount']); ?> BOXES SOLD!</h2>
                            <br>
                            Price per box: PHP<?php echo number_format($item['itemPrice'], 2); ?>
                            <br>
                            Stock: <?php echo number_format($item['itemStock']); ?> boxes
                        </h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-nav">
                Contact Number: <a>0976-525-4721</a>
                Email Address: <a>delta.trading@gmail.com</a>
            </div>
            <img class="easter-egg" src="images\arisbm.gif">
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
