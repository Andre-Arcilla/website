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

    // SQL query to fetch specific columns for "TISS", "HEAD", and "FACE" items
    $sql = "SELECT itemID, itemPrice, itemStock, soldAmount FROM items";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are any rows returned
        while ($row = $result->fetch_assoc()) {
            // Access data from the row
            $itemName = $row["itemID"];
            $itemPrice = $row["itemPrice"];
            $itemStock = $row["itemStock"];
            $soldAmount = $row["soldAmount"];

            // Assign data to respective variables based on item name
            switch ($itemName) {
                case 'TISS':
                    $tissuePrice = $itemPrice;
                    $tissueStock = $itemStock;
                    $tissueSoldAmount = $soldAmount;
                    break;
                case 'HEAD':
                    $headPrice = $itemPrice;
                    $headStock = $itemStock;
                    $headSoldAmount = $soldAmount;
                    break;
                case 'FACE':
                    $facePrice = $itemPrice;
                    $faceStock = $itemStock;
                    $faceSoldAmount = $soldAmount;
                    break;
                default:
                    // Handle unexpected item name
                    break;
            }
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
    
    <div class="hero">
        <div class="hero-container">
            <img src="images\DCT no bg.png">
            <h1>Find the Best Medical Supplies</h1>
            <b>Your one-stop shop for quality medical supplies.</b>
            <?php
                // Check if success parameter is set and display success message
                if (isset($_GET["success"]) && $_GET["success"] == 1) {
                    echo "<hr><p class='success-message'>Order placed successfully!</p>";
                }
            ?>
        </div>
    </div>

    <div class="featured-products">
        <h2>Best Sellers</h2>
        <div class="product-grid">
            <div class="product" onclick="toggleProduct(this, 0)">
                <div>
                    <img src="images/products/Tissues.png" alt="Product 1">
                    <h3>TISSUES</h3>
                </div>
                <div class="description">
                    <h3>
                        <h2><?php echo $tissueSoldAmount; ?> BOXES SOLD!</h2>
                        Price per box: PHP<?php echo $tissuePrice; ?>
                        Stock: <?php echo $tissueStock; ?> boxes
                    </h3>
                </div>
            </div>
            <div class="product" onclick="toggleProduct(this, 1)">
                <div>
                    <img src="images/products/Face Masks.png" alt="Product 2">
                    <h3>FACE MASKS</h3>
                </div>
                <div class="description">
                    <h3>
                        <h2><?php echo $faceSoldAmount; ?> BOXES SOLD!</h2>
                        Price per box: PHP<?php echo $facePrice; ?>
                        Stock: <?php echo $faceStock; ?> boxes
                    </h3>
                </div>
            </div>
            <div class="product" onclick="toggleProduct(this, 2)">
                <div>
                    <img src="images/products/Head Caps.png" alt="Product 3">
                    <h3>HEAD CAPS</h3>
                </div>
                <div class="description">
                    <h3>
                        <h2><?php echo $headSoldAmount; ?> BOXES SOLD!</h2>
                        Price per box: PHP<?php echo $headPrice; ?>
                        Stock: <?php echo $headStock; ?> boxes
                    </h3>
                </div>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const products = document.querySelectorAll(".product");

            products.forEach(product => {
                product.addEventListener("click", function() {
                    // Toggle the 'expanded' class on the clicked product
                    this.classList.toggle("expanded");

                    // Toggle the visibility of the description div
                    const description = this.querySelector(".description");
                    if (description) {
                        description.style.display = this.classList.contains("expanded") ? "block" : "none";
                    }

                    // Collapse other expanded products
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
    </script>
</body>
</html>

<?php
    // Close the database connection
    $conn->close();
?>