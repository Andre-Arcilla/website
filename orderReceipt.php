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

if (isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];
}

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
}

// SQL query to retrieve order information along with customer and item details, grouped by orderID
$sql = "SELECT order_info.orderID, 
        accounts.name,
        accounts.emailaddress,
        accounts.phonenumber,
        order_info.orderAddress, 
        order_info.orderDate, 
        order_info.orderPWD,
        order_info.orderSeniorCitizen,
        order_info.orderTotal,
        order_info.orderstatus,
        payments.gcashName,
        payments.gcashNumber,
        payments.gcashReferenceNum
    FROM order_info 
    INNER JOIN order_items ON order_info.orderID = order_items.orderID 
    INNER JOIN accounts ON order_info.accountID = accounts.accountID 
    INNER JOIN items ON order_items.itemID = items.itemID 
    LEFT JOIN payments ON order_info.orderID = payments.orderID
    WHERE order_info.orderID = ?
    GROUP BY order_info.orderID";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();

// Fetch order details
$orderDetails = $result->fetch_assoc();

$stmt->close();

// Retrieve items in the order
$sql_items = "SELECT items.itemName, items.itemPrice, order_items.itemAmount, order_items.totalPrice 
                FROM order_items 
                INNER JOIN items ON order_items.itemID = items.itemID 
                WHERE order_items.orderID = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $orderID);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

// Fetch all order items
$orderItems = [];
while ($row = $result_items->fetch_assoc()) {
    $orderItems[] = $row;
}

$stmt_items->close();
$conn->close();
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


            <?php if (isset($_GET["success"]) && $_GET["success"] == 1): ?>
                <div>
                    <b>Thank you for shopping, <?php echo $_SESSION["name"]; ?> !</b>
                </div>
            <?php elseif (isset($_SESSION["userid"])): ?>
                <div>
                    <b>Here's the order information, <?php echo $_SESSION["name"]; ?>.</b>
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
        <div class="contents-row1">
            <div class="title">
                <button onclick="location.href='orderInfo.php';" class="invisible">Previous Step</button>
                <span>Order <?php echo $orderID; ?> Receipt</span>
                <button onclick="location.href='actions/checkout-action.php';" class="invisible">CHECKOUT</button>
            </div>
        
            <div class="table-wrapper">
                <table class="itemslist-table" id="receipt">
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

                        foreach ($orderItems as $item) {
                            $itemName = $item['itemName'];
                            $itemAmount = $item['itemAmount'];
                            $itemPrice = $item['itemPrice'];
                            $subtotal = $itemPrice * $itemAmount;

                            $discount = 0;
                            if ($itemAmount >= 200) {
                                $discount = 0.2;
                            } elseif ($itemAmount >= 100) {
                                $discount = 0.1;
                            } elseif ($itemAmount >= 50) {
                                $discount = 0.05;
                            } elseif ($itemAmount >= 25) {
                                $discount = 0.025;
                            }

                            $discountAmount = $subtotal * $discount;
                            $discountedSubtotal = $subtotal - $discountAmount;
                            $total = $total + $discountedSubtotal;

                            echo "<tr>
                                    <td>$itemName</td>
                                    <td>$itemAmount</td>
                                    <td>PHP " . number_format($itemPrice, 2) . "</td>
                                    <td>" . ($discount * 100) . "%</td>
                                    <td>PHP " . number_format($discountAmount, 2) . "</td>
                                    <td>PHP " . number_format($discountedSubtotal, 2) . "</td>
                                </tr>";
                        }

                        $pwd = 0;
                        if ($orderDetails['orderPWD'] !== "PWD ID: NO PWD") {
                            $pwd = 0.10;
                        }

                        $sc = 0;
                        if ($orderDetails['orderSeniorCitizen'] !== "Senior Citizen ID: NO SC") {
                            $sc = 0.10;
                        }

                        $pwdDiscount = $total * $pwd;
                        $scDiscount = $total * $sc;

                        $discounts = $pwdDiscount + $scDiscount;
                        $discountedTotal = $total - $discounts;

                        $vatTotal = $discountedTotal * 0.12;
                        $grandTotal = $discountedTotal + $vatTotal;

                        echo "<tr>
                            <td colspan='5'><h3>Total:</h3></td>
                            <td><h3>PHP " . number_format($total, 2) . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='5'><h3>PWD Discount (10%):</h3></td>
                            <td><h3>-PHP " . number_format($pwdDiscount, 2) . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='5'><h3>Senior Citizen Discount (10%):</h3></td>
                            <td><h3>-PHP " . number_format($scDiscount, 2) . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='5'><h3>VAT (12%):</h3></td>
                            <td><h3>PHP " . number_format($vatTotal, 2) . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='5'><h3>Grand Total:</h3></td>
                            <td><h3>PHP " . number_format($grandTotal, 2) . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>Address</h3></td>
                            <td colspan='4'><h3>" . $orderDetails['orderAddress'] . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>PWD ID</h3></td>
                            <td colspan='4'><h3>" . $orderDetails['orderPWD'] . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>Senior Citizen ID</h3></td>
                            <td colspan='4'><h3>" . $orderDetails['orderSeniorCitizen'] . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>Gcash Name</h3></td>
                            <td colspan='4'><h3>" . $orderDetails['gcashName'] . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>Gcash Number</h3></td>
                            <td colspan='4'><h3>" . $orderDetails['gcashNumber'] . "</h3></td>
                        </tr>";

                        echo "<tr>
                            <td colspan='2'><h3>Gcash Reference Number</h3></td>
                            <td colspan='4'><h3>" . $orderDetails['gcashReferenceNum'] . "</h3></td>
                        </tr>";
                        ?>
                    </tbody>
                </table>
                <br>
                <button onclick="saveDivAsImage('receipt')">Print Receipt</button>
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

    <script src="html2canvas.min.js"></script>

    <script>
        function saveDivAsImage(divId) {
            var divElement = document.getElementById(divId);

            html2canvas(divElement).then(function(canvas) {
                var imgData = canvas.toDataURL('image/png');

                // Create a link element
                var link = document.createElement('a');
                link.href = imgData;
                link.download = 'order_<?php echo $orderID; ?>_receipt.png';

                // Trigger the download by simulating a click
                link.click();
            });
        }
    </script>
</body>
</html>
