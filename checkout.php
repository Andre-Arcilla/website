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

    // Remove item from cart if remove button is clicked
    if (isset($_POST["remove_item"]) && isset($_POST["itemID"])) {
        $itemIDToRemove = $_POST["itemID"];
        if (isset($_SESSION["cart"][$itemIDToRemove])) {
            unset($_SESSION["cart"][$itemIDToRemove]);
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
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="navbar">
                <img class="header-logo" src="images/DCT no bg v2.png">
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
        <div class="contents-row1">
            <div class="title">
                <button onclick="location.href='products.php';">Back to Store</button>
                <span>CART SUMMARY</span>
            </div>
        
            <div class="table-wrapper">
                <table class="itemslist-table">
                    <thead>
                        <tr>
                            <th>Remove Item</th>
                            <th>Item Name</th>
                            <th>Item Amount</th>
                            <th>Price per Item</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;

                        if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
                            foreach ($_SESSION["cart"] as $itemID => $quantity) {
                                // Retrieve item details from the database
                                $sql = "SELECT itemName, itemPrice FROM items WHERE itemID = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $itemID);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $itemName = $row["itemName"];
                                    $itemPrice = $row["itemPrice"];
                                    $subtotal = number_format($itemPrice * $quantity, 2);
                                    $total = $subtotal + $total;
                                
                                    echo "<tr>
                                            <td>
                                                <form method='post'>
                                                    <input type='hidden' name='itemID' value='$itemID'>
                                                    <button type='submit' name='remove_item'>Remove</button>
                                                </form>
                                            </td>
                                            <td>$itemName</td>
                                            <td>$quantity</td>
                                            <td>PHP " . number_format($itemPrice, 2) . "</td>
                                            <td>PHP " . number_format($subtotal, 2) . "</td>
                                        </tr>";
                                }
                                $stmt->close();
                            }
                        } else {
                            echo "<tr><td colspan='5'>Your cart is empty</td></tr>";
                        }
                        ?>

                        <tr>
                            <?php echo "<th colspan='5' class='total-row'>Total: PHP ".number_format($total, 2)."</th>"; ?>
                        </tr>
                    </tbody>
                </table>

            
            </div>
        </div>

        <div class="confirm-box">
            <form action="payment.php" method="post" onsubmit="return validateForm()">
                <div class="address-box">
                    <div class="input-box">
                        <label>STREET ADDRESS: </label>
                        <input type="text" name="street" placeholder="456 Sunset Boulevard" required>
                    </div>

                    <div class="input-box">
                        <label>BARANGAY: </label>
                        <input type="text" name="barangay" placeholder="Barangay Sunshine" required>

                        
                        <label>CITY: </label>
                        <input type="text" name="city" placeholder="Manila" required>
                    </div>

                    <div class="input-box">
                        <label>PROVINCE: </label>
                        <input type="text" name="province" placeholder="Metro Manila" required>

                        <label>POSTAL CODE: </label>
                        <input type="text" name="postal" placeholder="1008" required>
                    </div>
                </div>

                <div class="consent-box">
                    <input type="checkbox" name="refund_agreement" id="refund_agreement">
                    <label for="refund_agreement">
                        I understand that Delta Chemical Trading is not obligated to issue a refund for a canceled order.
                    </label>
                    <br>
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-nav">
                <a href="#">Home</a>
                <a href="#">Store</a>
            </div>
            <img class="easter-egg" src="images/arisbm.gif">
        </div>
    </footer>

    <script>
        function validateForm() {
            var refund_agreement = document.getElementById("refund_agreement").checked;
            if (!refund_agreement) {
                alert("Please agree to the refund policy.");
                return false; // Prevent form submission
            }
            
            // Additional validations or form submission logic can be added here
            
            return true; // Allow form submission
        }
    </script>
</body>
</html>
