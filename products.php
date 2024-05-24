<?php
    session_start();

    // Check if the user is not logged in
    if (!isset($_SESSION["userid"])) {
        // Redirect to the login page
        header("Location: login.php");
        exit(); // Stop further execution
    }

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

    // Check if the "add to cart" button is clicked
    if (isset($_POST["add_to_cart"])) {
        // Get the item ID and quantity from the form submission
        $itemID = $_POST["item_id"];
        $quantity = $_POST["quantity"];

        // Ensure the quantity is greater than 0
        if ($quantity > 0) {
            // Initialize the cart session variable if it doesn't exist
            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
            }

            // Check if the item is already in the cart
            if (isset($_SESSION["cart"][$itemID])) {
                // If so, update the quantity
                $_SESSION["cart"][$itemID] += $quantity;
            } else {
                // If not, add the item to the cart
                $_SESSION["cart"][$itemID] = $quantity;
            }

            // Redirect to prevent form resubmission on page refresh
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit();
        }
    }

    // Check if the "empty_cart" button is clicked
    if (isset($_POST["empty_cart"])) {
        // Clear the cart session variable
        unset($_SESSION["cart"]);
        // Redirect back to the same page
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }

    // Function to calculate the total price of items in the cart
    function calculateTotalPrice() {
        $totalPrice = 0;

        // Loop through each item in the cart
        foreach ($_SESSION["cart"] as $itemID => $quantity) {
            // Get the price of the item from the database
            global $conn;
            $sql = "SELECT itemPrice FROM items WHERE itemID = '$itemID'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $itemPrice = $row["itemPrice"];
                // Calculate the total price for this item (price * quantity)
                $totalPrice += $itemPrice * $quantity;
            }
        }

        return $totalPrice;
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
                <img class="header-logo" src="images/DCT no bg v2.png" alt="Logo">
                <nav class="navigation">
                    <a href="index.php">Home</a>
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
        <div class="product-grid">
            <?php
            // Retrieve items from the database
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="product">
                        <img src="images/products/<?php echo $row["itemName"]; ?>.png" alt="<?php echo $row["itemName"]; ?>">
                        <h3><?php echo $row["itemName"]; ?></h3>
                        <p>Price: PHP <?php echo $row["itemPrice"]; ?></p>
                        <p>Stock: <?php echo $row["itemStock"]; ?></p>
                        <!-- Add to cart form -->
                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php echo $row["itemID"]; ?>">
                            <div class="number-input">
                                <button type="button" onclick="decrement('quantity-<?php echo $row["itemID"]; ?>')">-</button>
                                <input type="text" value="0" min="0" max="<?php echo $row["itemStock"]; ?>" id="quantity-<?php echo $row["itemID"]; ?>" name="quantity">
                                <button type="button" onclick="increment('quantity-<?php echo $row["itemID"]; ?>')">+</button>
                            </div>
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo "No items found";
            }
            ?>
        </div>
        
        <div class="cart-box">
            <!-- Cart summary -->
            <div class="cart-summary">
                <h2>Cart Summary</h2>
                <?php if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])): ?>
                    <ul>
                        <?php foreach ($_SESSION["cart"] as $itemID => $quantity): ?>
                            <?php
                            // Retrieve item details from the database
                            $sql = "SELECT itemName, itemPrice FROM items WHERE itemID = '$itemID'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $itemName = $row["itemName"];
                                $itemPrice = $row["itemPrice"];
                                ?>
                                <li>
                                    <b><?php echo $itemName; ?></b>
                                    <br>
                                    Quantity: <b><?php echo $quantity; ?></b>
                                    <br>
                                    Price: <b>PHP <?php echo $itemPrice * $quantity; ?></b>
                                </li>
                                <br>
                                <?php
                            }
                            ?>
                        <?php endforeach; ?>
                    </ul>
                    <h4>Total Price: PHP <?php echo calculateTotalPrice(); ?></h4>
                    
                    <form action="checkout.php" method="post">
                        <button type="submit" name="checkout">Checkout</button>
                    </form>
                    <form method="post">
                        <button type="submit" name="empty_cart">Empty Cart</button>
                    </form>
                <?php else: ?>
                    <p>Your cart is empty</p>
                <?php endif; ?>
            </div>
        </div>
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
    
    <script>
        // Increment function to increase quantity
        function increment(id) {
            var input = document.getElementById(id);
            var value = parseInt(input.value, 10);
            var max = parseInt(input.max, 10);
            input.value = value < max ? value + 1 : max;
        }

        // Decrement function to decrease quantity
        function decrement(id) {
            var input = document.getElementById(id);
            var value = parseInt(input.value, 10);
            input.value = value > 0 ? value - 1 : 0;
        }

        // Validate input value to ensure it doesn't exceed max for each item
        document.querySelectorAll('input[type="text"]').forEach(function(input) {
            input.addEventListener('input', function() {
                var value = parseInt(this.value, 10);
                var max = parseInt(this.max, 10);
                this.value = isNaN(value) ? 0 : value > max ? max : value;
            });
        });
    </script>
</body>
</html>

<?php
    // Close the database connection
    $conn->close();
?>