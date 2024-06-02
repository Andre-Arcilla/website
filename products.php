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

    if ($quantity > 0) {
        // Update the quantity in the cart to match the input field value
        $_SESSION["cart"][$itemID] = $quantity;
    } elseif ($quantity == 0) {
        // Remove the item from the cart
        unset($_SESSION["cart"][$itemID]);
    }

    // Redirect to prevent form resubmission on page refresh
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
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
                <img id="header-logo" class="header-logo" src="images/DCT no bg v2.png" alt="Logo">
                <nav class="navigation">
                    <button class="sidebar-button" onclick="location.href='index.php';">Home</button>
                    <button class="sidebar-button" onclick="location.href='products.php';">Store</button>
                    <button class="sidebar-button" onclick="location.href='orders.php';">Your Orders</button>
                </nav>
            </div>

            <?php if (isset($_SESSION["userid"])): ?>
                <div>
                    <b>Planning to buy some medical supplies, <?php echo $_SESSION["name"]; ?> ?</b>
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

    <div class="contents">
        <div class="product-grid">
            <?php
            // Retrieve items from the database
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Adjust stock based on items in the cart
                    $itemID = $row["itemID"];
                    $itemStock = $row["itemStock"];
                    if (isset($_SESSION["cart"][$itemID])) {
                        $itemStock -= $_SESSION["cart"][$itemID];
                    }
                    ?>
                    <div class="product">
                        <img src="images/products/<?php echo $row["itemName"]; ?>.png" alt="<?php echo $row["itemName"]; ?>">
                        <div>
                            <h3><?php echo $row["itemName"]; ?></h3>
                            <p>Price: PHP <?php echo number_format($row["itemPrice"], 2); ?></p>
                            <p>Stock: <?php echo number_format($itemStock); ?></p>
                            <!-- Add to cart form -->
                            <form method="post" onsubmit="updateQuantity('<?php echo $row["itemID"]; ?>')">
                                <input type="hidden" name="item_id" value="<?php echo $row["itemID"]; ?>">
                                <div class="number-input">
                                    <button type="button" onclick="decrement('quantity-<?php echo $row["itemID"]; ?>')">-</button>
                                    <input type="text" value="<?php echo isset($_SESSION['cart'][$row['itemID']]) ? $_SESSION['cart'][$row['itemID']] : 0; ?>" min="0" max="<?php echo $itemStock; ?>" id="quantity-<?php echo $row["itemID"]; ?>" name="quantity">
                                    <button type="button" onclick="increment('quantity-<?php echo $row["itemID"]; ?>')">+</button>
                                </div>
                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </div>
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
                <h2>Cart Summary:</h2>
                <?php if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])): ?>
                    <div class="cart-items">
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
                                        Quantity: <b><?php echo number_format($quantity); ?></b>
                                        <br>
                                        Price: <b>PHP <?php echo number_format(($itemPrice * $quantity), 2); ?></b>
                                    </li>
                                    <br>
                                    <?php
                                }
                                ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <h4>Total Price: PHP <?php echo number_format(calculateTotalPrice(), 2); ?></h4>
                        <form action="orderTotal.php" method="post">
                            <button type="submit" name="checkout">Checkout</button>
                        </form>
                        <form method="post">
                            <button type="submit" name="empty_cart">Empty Cart</button>
                        </form>
                    </div>
                <?php else: ?>
                    <h2>Your cart is empty</h2>
                <?php endif; ?>
            </div>
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
        
        function updateQuantity(itemID) {
            var input = document.getElementById('quantity-' + itemID);
            var quantity = parseInt(input.value);
            if (!isNaN(quantity) && quantity >= 0) {
                input.value = quantity; // Ensure input reflects the value
            }
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
