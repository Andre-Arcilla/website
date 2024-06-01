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
    <script src="html2canvas.min.js"></script>
    <script>
        function saveRowAsImage(rowId) {
            var rowElement = document.getElementById(rowId);

            html2canvas(rowElement).then(function(canvas) {
                var imgData = canvas.toDataURL('image/png');

                // Create a link element
                var link = document.createElement('a');
                link.href = imgData;
                link.download = 'order_receipt.png';

                // Simulate a click on the link to trigger the download
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }
    </script>
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
                    <tr id="header-row">
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Order Address</th>
                        <th>Order GCash</th>
                        <th>Discounts Details</th>
                        <th>Item Details</th>
                        <th>Order Total</th>
                        <th colspan='2'>Order Status</th>
                        <th>Print Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Check if there are orders for the user
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {

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

                                // Assign a unique ID to each row
                                $rowId = "row-{$row['orderID']}";

                                echo "<tr id='$rowId'>
                                    <td>{$row['orderID']}</td>
                                    <td>{$row['orderDate']}</td>
                                    <td>{$row['orderAddress']}</td>
                                    <td>$gcash</td>
                                    <td class='item-alignment'>{$row['orderDiscounts']}</td>
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
                                    <td>{$row['orderstatus']}</td>
                                    <td>";

                                    if ($row['orderstatus'] != 'cancelled' && $row['orderstatus'] != 'shipping' && $row['orderstatus'] != 'delivered') {
                                    echo "
                                        <form method='post' action=''>
                                            <input type='hidden' name='orderID' value='{$row['orderID']}'>
                                            <input type='hidden' name='newStatus' value='cancelled'>
                                            <button type='submit'>Cancel</button>
                                        </form>";
                                    } elseif ($row['orderstatus'] == 'cancelled') {
                                        echo "ORDER<BR>CANCELED";
                                    } elseif ($row['orderstatus'] == 'shipping') {
                                        echo "ORDER<BR>SHIPPING";
                                    } elseif ($row['orderstatus'] == 'delivered') {
                                        echo "ORDER<BR>DELIVERED";
                                    }
                                    echo "</td>
                                    <td>
                                        <button onclick=\"saveDivAsImage('$rowId')\">Print Receipt</button>
                                    </td>
                                </tr>";                                
                            }
                        } else {
                            echo "<tr><td colspan='10'>No orders found for this user.</td></tr>";
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

    <script src="html2canvas.min.js"></script>

    <script>
        function saveDivAsImage(divId) {
            var divElement = document.getElementById(divId);
            document.querySelector('.itemslist-table').style.maxHeight = '100rem';

            // Capture the image
            html2canvas(divElement).then(function(canvas) {
                var imgData = canvas.toDataURL('image/png');

                // Create a link element
                var link = document.createElement('a');
                link.href = imgData;
                link.download = 'download.png';

                // Simulate a click on the link to trigger the download
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Set max-height back to 15rem after capturing the image
                document.querySelector('.itemslist-table').style.maxHeight = '15rem';
            });
        }
    </script>
</body>
</html>
