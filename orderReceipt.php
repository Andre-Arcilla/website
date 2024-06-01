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

    //checks if pwd
    if ($_SESSION['pwd-checkbox'] == "checked" && $_SESSION['pwd-token'] == 0) {
        $_SESSION['pwd-checkbox'] = "checked";
        $pwd = 0.10;
        $_SESSION['pwd-token'] = 1;
    } elseif (isset($_POST['pwdID'])) {
        $_SESSION['pwd-checkbox'] = "checked";
        $pwd = 0.10;
        $_SESSION['pwd-token'] = 1;
    } else {
        $_SESSION['pwd-checkbox'] = "";
        $pwd = 0;
        $_SESSION['pwd-token'] = 1;
    }

    //checks if sc
    if ($_SESSION['sc-checkbox'] == "checked" && $_SESSION['sc-token'] == 0) {
        $_SESSION['sc-checkbox'] = "checked";
        $sc = 0.10;
        $_SESSION['sc-token'] = 1;
    } elseif (isset($_POST['scID'])) {
        $_SESSION['sc-checkbox'] = "checked";
        $sc = 0.10;
        $_SESSION['sc-token'] = 1;
    } else {
        $_SESSION['sc-checkbox'] = "";
        $sc = 0;
        $_SESSION['sc-token'] = 1;
    }

    if (isset($_POST['pwdID'])) {
        $_SESSION['pwdID'] = $_POST['pwdID'];
    } else {
        $_SESSION['pwdID'] = 'NO ID';
    }

    if (isset($_POST['scID'])) {
        $_SESSION['scID'] = $_POST['scID'];
    } else {
        $_SESSION['scID'] = 'NO ID';
    }

    $_SESSION['gcashName'] = $_POST['gcashName'];
    $_SESSION['gcashNumber'] = $_POST['gcashNumber'];
    $_SESSION['gcashReferenceNum'] = $_POST['gcashReferenceNum'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Supplies Online</title>
    <link rel="stylesheet" href="orderTotal.css">
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
        <div class="contents-row1">
            <div class="title">
                <button onclick="location.href='orderInfo.php';">Previous Step</button>
                <span>Order Receipt</span>
                <button onclick="location.href='actions/checkout-action.php';">Next Step</button>
            </div>
        
            <div class="table-wrapper">
                <table class="itemslist-table">
                    <thead>
                        <tr>
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
                                    if ($quantity >= 200) {
                                        $discount = 0.2;
                                    } elseif ($quantity >= 100) {
                                        $discount = 0.1;
                                    } elseif ($quantity >= 50) {
                                        $discount = 0.05;
                                    } elseif ($quantity >= 25) {
                                        $discount = 0.025;
                                    }

                                    $subtotal = $itemPrice * $quantity;
                                    $discountprice = $subtotal * $discount;
                                    $subtotal = $subtotal - $discountprice;
                                    $total = $subtotal + $total;

                                    $pwdDiscount = $total * $pwd;
                                    $scDiscount = $total * $sc;

                                    $discounts = $pwdDiscount + $scDiscount;
                                    $discountedTotal = $total - $discounts;

                                    $vatTotal = $discountedTotal * 0.12;
                                    $grandTotal = $discountedTotal + $vatTotal;

                                    echo "<tr>
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
                            echo "<tr><td colspan='5'>Your cart is empty</td></tr>";
                        }
                        
                        echo "<tr>
                            <td colspan='5'><h3>Total:</h3></td>
                            <td><h3>PHP ".$total."</h3></td>
                        </tr>";

                        if (isset($_POST['pwdID'])) {
                            echo "<tr>
                                <td colspan='5'><h3>PWD Discount (10%):</h3></td>
                                <td><h3>-PHP ".number_format($pwdDiscount, 2)."</h3></td>
                            </tr>";
                        }

                        if (isset($_POST['scID'])) {
                            echo "<tr>
                                <td colspan='5'><h3>Senior Citizen Discount (10%):</h3></td>
                                <td><h3>-PHP ".number_format($scDiscount, 2)."</h3></td>
                            </tr>";
                        }
                        
                        echo "<tr>
                            <td colspan='5'><h3>VAT (12%):</h3></td>
                            <td><h3>PHP ".number_format($vatTotal, 2)."</h3></td>
                        </tr>";
                        
                        echo "<tr>
                            <td colspan='5'><h3>Grand Total:</h3></td>
                            <td><h3>PHP ".number_format($grandTotal, 2)."</h3></td>
                        </tr>";
                        
                        echo "<tr>
                            <td colspan='2'><h3>Address</h3></td>
                            <td colspan='4'><h3>".$_SESSION['address']."</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>PWD ID</h3></td>
                            <td colspan='4'><h3>".$_SESSION['pwdID']."</h3></td>
                        </tr>";
                        
                        echo "<tr>
                            <td colspan='2'><h3>Senior Citizen ID</h3></td>
                            <td colspan='4'><h3>".$_SESSION['scID']."</h3></td>
                        </tr>";
                        
                        echo "<tr>
                            <td colspan='2'><h3>Gcash Name</h3></td>
                            <td colspan='4'><h3>".$_SESSION['gcashName']."</h3></td>
                        </tr>";
                        
                        echo "<tr>
                            <td colspan='2'><h3>Gcash Number</h3></td>
                            <td colspan='4'><h3>".$_SESSION['gcashNumber']."</h3></td>
                        </tr>";
                        
                        echo "<tr>
                            <td colspan='2'><h3>Gcash Reference Number</h3></td>
                            <td colspan='4'><h3>".$_SESSION['gcashReferenceNum']."</h3></td>
                        </tr>";
                        ?>
                    </tbody>
                </table>

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
