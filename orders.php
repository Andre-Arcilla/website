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

// Check if form is submitted for canceling orders
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderID']) && isset($_POST['newStatus']) && $_POST['newStatus'] == 'cancelled') {
    $orderID = $_POST['orderID'];
    $newStatus = 'cancelled';
    
    // SQL query to update the order status to 'cancelled'
    $updateSql = "UPDATE order_info SET orderStatus = ? WHERE orderID = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, 'si', $newStatus, $orderID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Retrieve the user ID from the session
$userId = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;

// Query to fetch orders and their items for the user
$sql = "SELECT oi.orderID, oi.orderDate, oi.orderAddress, oi.orderTotal, oi.orderStatus, oi.accountID,
                GROUP_CONCAT(CONCAT(it.itemName, ' - ', oit.itemAmount, 'x - ', oit.totalPrice) SEPARATOR '|') AS itemDetails
        FROM order_info oi
        JOIN order_items oit ON oi.orderID = oit.orderID
        JOIN items it ON oit.itemID = it.itemID
        WHERE oi.accountID = $userId
        GROUP BY oi.orderID";

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Supplies Online</title>
    <link rel="stylesheet" href="orders.css">
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
        <div class="table-wrapper">
            <table class="outer-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Order Address</th>
                        <th>Item Details</th>
                        <th>Order Total</th>
                        <th colspan='2'>Order Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Check if there are orders for the user
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['orderID']}</td>
                                    <td>{$row['orderDate']}</td>
                                    <td>{$row['orderAddress']}</td>
                                    <td>
                                        <div class='itemslist-table'>
                                            <table class='inner-table'>
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Quantity</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                            
                                            // Split the itemDetails into an array
                                            $items = explode('|', $row['itemDetails']);
                                            foreach ($items as $item) {
                                                list($itemName, $itemQuantity, $itemPrice) = explode(' - ', $item);
                                                echo "<tr>
                                                    <td>$itemName</td>
                                                    <td>$itemQuantity</td>
                                                    <td>PHP ".number_format($itemPrice, 2)."</td>
                                                </tr>";
                                            }
                                            
                                            echo "</tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td>PHP ".number_format($row['orderTotal'], 2)."</td>
                                    <td>{$row['orderStatus']}</td>
                                    <td>";

                                    if ($row['orderStatus'] != 'cancelled' && $row['orderStatus'] != 'shipping' && $row['orderStatus'] != 'delivered') {
                                    echo "
                                        <form method='post' action=''>
                                            <input type='hidden' name='orderID' value='{$row['orderID']}'>
                                            <input type='hidden' name='newStatus' value='cancelled'>
                                            <button type='submit'>Cancel</button>
                                        </form>";
                                    } elseif ($row['orderStatus'] == 'cancelled') {
                                        echo "ORDER<BR>CANCELED";
                                    } elseif ($row['orderStatus'] == 'shipping') {
                                        echo "ORDER<BR>SHIPPING";
                                    } elseif ($row['orderStatus'] == 'delivered') {
                                        echo "ORDER<BR>DELIVERED";
                                    }
                                    echo "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No orders found for this user.</td></tr>";
                        }
                        // Close the database connection
                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-nav">
                Contact Number: <a>0976-525-4721</a>
                Email Address: <a>delta.trading@gmail.com</a>
            </div>
            <img class="easter-egg" src="images/arisbm.gif">
        </div>
    </footer>
</body>
</html>
