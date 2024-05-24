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

    // Function to cancel an order
    function cancelOrder($conn, $orderID) {
        // Sanitize and validate the input
        $orderID = mysqli_real_escape_string($conn, $orderID);

        // Update the order status to "cancelled"
        $sql = "UPDATE order_info SET orderStatus = 'cancelled' WHERE orderID = '$orderID'";
        if ($conn->query($sql) === TRUE) {
        } else {
        }
    }

    // Check if form is submitted for updating order status
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderID']) && isset($_POST['currentStatus'])) {
        $orderID = $_POST['orderID'];
        $currentStatus = $_POST['currentStatus'];
        
        // Determine the next status
        $nextStatus = '';
        if ($currentStatus == 'processing') {
            $nextStatus = 'shipping';
        } elseif ($currentStatus == 'shipping') {
            $nextStatus = 'delivered';
        } elseif ($currentStatus == 'cancelled') {
            $nextStatus = 'processing';
        }

        if ($nextStatus) {
            // SQL query to update the order status
            $updateSql = "UPDATE order_info SET orderstatus = ? WHERE orderID = ?";
            $stmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($stmt, 'si', $nextStatus, $orderID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // Check if form is submitted for canceling orders
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderID']) && isset($_POST['newStatus']) && $_POST['newStatus'] == 'cancelled') {
        $orderID = $_POST['orderID'];
        $newStatus = $_POST['newStatus'];
        
        // SQL query to update the order status to 'cancelled'
        $updateSql = "UPDATE order_info SET orderstatus = ? WHERE orderID = ?";
        $stmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($stmt, 'si', $newStatus, $orderID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Retrieve the user ID from the session
    $userId = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;

    // Query to fetch orders and their items for the user
    $sql = "SELECT oi.orderID, oi.orderDate, oi.orderTotal, oi.orderStatus, oi.accountID,
                    GROUP_CONCAT(CONCAT(items.itemName, ' ', oit.itemAmount, 'x') SEPARATOR ' | ') AS itemDetails
            FROM order_info oi
            JOIN order_items oit ON oi.orderID = oit.orderID
            JOIN items ON oit.itemID = items.itemID
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
    <link rel="stylesheet" href="styles2.css">
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

    <div class="contents">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Item Details</th>
                        <th>Order Total</th>
                        <th>Order Status</th>
                        <th>Cancel Order</th>
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
                                    <td>{$row['itemDetails']}</td>
                                    <td>{$row['orderTotal']}</td>
                                    <td>{$row['orderStatus']}</td>
                                    <td>";

                                    if ($row['orderStatus'] != 'cancelled') {
                                    echo "<form method='post' action=''>
                                        <input type='hidden' name='orderID' value='{$row['orderID']}'>
                                        <input type='hidden' name='newStatus' value='cancelled'>
                                        <button type='submit'>Cancel</button>
                                    </form>";
                                    }
                                    echo "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No orders found for this user.</td></tr>";
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
</body>
</html>
