<?php
    session_start();

    // Check if the usertype is not set or is not 'admin'
    if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] != 'admin') {
        // Redirect to the login page
        header("Location: ../login.php");
        exit(); // Stop further execution
    }

    // Database connection variables
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "delta";

    // Connect to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
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

    // SQL query to retrieve order information along with customer and item details, grouped by orderID
    $sql = "SELECT order_info.orderID, 
        CONCAT('<b>Name:</b><br>', accounts.name, '<br><br><b>Email:</b><br>', accounts.emailaddress, '<br><br><b>Phone Number:</b><br>', accounts.phonenumber) AS customerInfo,
        order_info.orderAddress, 
        order_info.orderDate, 
        CONCAT(order_info.orderPWD, '<br><br>', order_info.orderSeniorCitizen) AS orderDiscounts,
        order_info.orderTotal,
        GROUP_CONCAT(CONCAT(items.itemName, ' - ', order_items.itemAmount, 'x - ', order_items.totalPrice) SEPARATOR '|') AS itemDetails,
        order_info.orderstatus,
        CONCAT('<b>Account Name:</b><br>', payments.gcashName, '<br><br><b>GCASH NUM:</b><br>', payments.gcashNumber, '<br><br><b>REF NUM:</b><br>', payments.gcashReferenceNum) AS gcashInfo
    FROM order_info 
    INNER JOIN order_items ON order_info.orderID = order_items.orderID 
    INNER JOIN accounts ON order_info.accountID = accounts.accountID 
    INNER JOIN items ON order_items.itemID = items.itemID 
    LEFT JOIN payments ON order_info.orderID = payments.orderID
    GROUP BY order_info.orderID 
    ORDER BY order_info.orderID ASC;";

    // Execute SQL query
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminViewOrders.css">
</head>
<body>
    <header>
        <img src="..\images\DCT no bg v3.png">
        <div class="main-website"><a href="#">back to main website</a></div>
    </header>
    <div class="sidebar">
        <button class="sidebar-button" id="hidden">aaaa</button>
        <button class="sidebar-button" onclick="location.href='adminStatistics.php';">statistics</button>
        <button class="sidebar-button" onclick="location.href='adminViewItems.php';">view items</button>
        <button class="sidebar-button" onclick="location.href='adminEditItems.php';">edit items</button>
        <button class="sidebar-button" id="selected">view orders</button>
    </div>
    <div class="main-content">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Info</th>
                    <th>Order Address</th>
                    <th>Order Date</th>
                    <th>Discounts Details</th>
                    <th>Order GCash</th>
                    <th>Item Details</th>
                    <th>Order Total</th>
                    <th>Order Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Check if there are any rows returned by the query
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='12'>No orders yet!</td></tr>";
                    } else {
                        // Loop through each row of the result set
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Split the item details into an array
                            $itemDetails = explode('|', $row['itemDetails']);
                            
                            // Start the list
                            $itemList = "<table>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>";
                            foreach ($itemDetails as $item) {
                                list($itemName, $itemQuantity, $itemPrice) = explode(' - ', $item);
                                $itemList .= "<tr>";
                                $itemList .= "<td>$itemName</td>";
                                $itemList .= "<td>$itemQuantity</td>";
                                $itemList .= "<td>".number_format($itemPrice, 2)."</td>";
                                $itemList .= "</tr>";
                            }
                            $itemList .= "</table>";

                            // Split the gcash info into an array
                            $gcashInfo = explode('|', $row['gcashInfo']);
                            $orderID1 = ($row['orderID']);
                            
                            // Start the list
                            $gcash = "";
                            $orderID2 = '';
                            foreach ($gcashInfo as $item) {
                                if ($orderID1 == $orderID2) {
                                    $orderID2 = $orderID1;
                                    break;
                                }
                                $gcash .= $item;
                                $orderID2 = $orderID1;
                            }
                            
                            // Output the table row
                            echo "<tr>
                                <td>{$row['orderID']}</td>
                                <td class='item-alignment'>{$row['customerInfo']}</td>
                                <td class='item-alignment'>{$row['orderAddress']}</td>
                                <td>{$row['orderDate']}</td>
                                <td class='item-alignment'>$gcash</td>
                                <td class='item-alignment'>{$row['orderDiscounts']}</td>
                                <td>
                                    <div class='itemslist-table'>
                                        $itemList
                                    </div>
                                </td>
                                <td>".number_format($row['orderTotal'], 2)."</td>
                                <td>{$row['orderstatus']}</td>
                                <td>
                                    <div class='buttons'>
                                        <form method='post' action=''>
                                            <input type='hidden' name='orderID' value='{$row['orderID']}'>
                                            <input type='hidden' name='currentStatus' value='{$row['orderstatus']}'>";

                                    switch ($row['orderstatus']) {
                                        case 'processing':
                                            echo "<input type='hidden' name='newStatus' value='shipping'>
                                                <button type='submit'>Ship Order</button>";
                                            break;
                                        case 'shipping':
                                            echo "<input type='hidden' name='newStatus' value='delivered'>
                                                <button type='submit'>Deliver Order</button>";
                                            break;
                                        default:
                                            echo "<h4>ORDER DELIVERED</h4>";
                                            break;
                                    }
                            echo "</form>";

                            if ($row['orderstatus'] != 'cancelled' && $row['orderstatus'] != 'delivered') {
                                echo "<hr><form method='post' action=''>
                                    <input type='hidden' name='orderID' value='{$row['orderID']}'>
                                    <input type='hidden' name='newStatus' value='cancelled'>
                                    <button type='submit'>Cancel Order</button>
                                </form>";
                                }
                        }
                        echo "</div></td></tr>";
                        }

                    // Close database connection
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
