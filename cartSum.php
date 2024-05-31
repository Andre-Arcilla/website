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
    <link rel="stylesheet" href="cartSum.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="navbar">
                <img class="header-logo" src="images/DCT no bg v2.png">
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
        <form action="orderInfo.php" method="post" class="contents-row1">
            <div class="title">
                <button onclick="location.href='products.php';">Previous Step</button>
                <span>Order Summary</span>
                <button type="submit" name="submit">Next Step</button>
            </div>
        
            <div class="table-wrapper">
                <table class="itemslist-table">
                    <thead>
                        <tr>
                            <th>Remove Item</th>
                            <th>Item Name</th>
                            <th>Item Amount</th>
                            <th>Price per Item</th>
                            <th>Discount</th>
                            <th>Discount Price</th>
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

                                    $discount = 0;
                                    if ($quantity >= 150) {
                                        $discount = 0.15;
                                    } elseif ($quantity >= 100) {
                                        $discount = 0.1;
                                    } elseif ($quantity >= 50) {
                                        $discount = 0.05;
                                    }

                                    $subtotal = $itemPrice * $quantity;
                                    $discountprice = $subtotal*$discount;
                                    $subtotal = $subtotal - $discountprice;
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
                                            <td>" . ($discount*100) . "%</td>
                                            <td>PHP " . number_format($discountprice, 2) . "</td>
                                            <td>PHP " . number_format($subtotal, 2) . "</td>
                                        </tr>";
                                }
                                $stmt->close();
                            }
                        } else {
                            echo "<tr><td colspan='7'>Your cart is empty</td></tr><tr>";
                        }

                            echo
                            "<th colspan='6' class='total-row'>
                                <div class='row-info'>
                                    <div class='checkbox'>
                                        <label>
                                            <input type='checkbox' name='pwd-checkbox' id='pwd-checkbox'>
                                            PWD DISCOUNT
                                        </label>
                                        <label>
                                            <input type='checkbox' name='sc-checkbox' id='sc-checkbox'>
                                            SENIOR CITIZEN DISCOUNT 
                                        </label>
                                    </div>
                                    <p>5% off for 50+ quantity  |  10% off for 100+ quantity  |  15% off for 150+ quantity</p>
                                </div>
                            </th>
                            <th class='total-row'>
                                <p>Total: PHP ".number_format($total, 2)."</p>
                            </th>";
                            ?>
                        </tr>
                    </tbody>
                </table>

            </div>
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
