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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <style>
        body {
            background-color: hsl(180, 12%, 45%);
            margin-top: 5vh;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: url('../images/backgrounds/bg2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        table {
            margin: 0;
            border-collapse: collapse; /* Ensure borders are collapsed into a single border */
            background-color: lightblue; /* Optional: Change background color to distinguish from table body */
        }

        th, td, #customer {
            padding: .5vw;
            font-size: 1vw;
            border: 1px solid black; /* Set border for cells */
        }

        tr:nth-child(even) {
            background-color: lightblue;
        }

        tr:nth-child(odd) {
            background-color: #8fcadd;
        }

        tr:hover {
            background-color: #73aabb;
        }

        th {
            position: sticky;
            top: 0; /* Stick the header row to the top of the viewport */
            background-color: deepskyblue  /* Optional: Change background color to distinguish from table body */
        }

        a {
            color: black;
            text-decoration: none !important;
            font-size: 1.5vw;
            margin-bottom: 4rem;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            text-align: left;
        }

        form {
            display: flex;
            height: 2.5rem;
        }

        button {
            flex: 1;
            height: 100%; /* Make buttons take up full height of their container */
        }

    </style>
</head>
<body>
    <!-- Return link -->
    <a href="adminmain.php">[RETURN]</a>

    <table>
        <thead>
            <tr>
                <th>CANCEL ORDERS</th>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Customer Email</th>
                <th>Customer Phone Number</th>
                <th>Order Date</th>
                <th>Order Total</th>
                <th>Item Details</th>
                <th>Order GCash</th>
                <th>Order Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // SQL query to retrieve order information along with customer and item details, grouped by orderID
                $sql = "SELECT order_info.orderID, 
                    accounts.name AS customerName, 
                    accounts.address AS customerAddress, 
                    accounts.emailaddress AS customerEmail, 
                    accounts.phonenumber AS customerPhoneNumber, 
                    order_info.orderDate, 
                    order_info.orderTotal, 
                    GROUP_CONCAT(CONCAT(items.itemName, ' ', order_items.itemAmount, 'x') SEPARATOR '<br>') AS itemDetails,
                    order_info.orderstatus,
                    GROUP_CONCAT(CONCAT(payments.gcashName, '<br><br>GCASH NUM: ', payments.gcashNumber, '<br><br>REF NUM: ', payments.gcashReferenceNum) SEPARATOR '|') AS gcashInfo
                FROM order_info 
                INNER JOIN order_items ON order_info.orderID = order_items.orderID 
                INNER JOIN accounts ON order_info.accountID = accounts.accountID 
                INNER JOIN items ON order_items.itemID = items.itemID 
                LEFT JOIN payments ON order_info.orderID = payments.orderID
                GROUP BY order_info.orderID 
                ORDER BY order_info.orderID ASC;";

                // Execute SQL query
                $result = mysqli_query($conn, $sql);

                // Check if there are any rows returned by the query
                if (mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='12'>No orders yet!</td></tr>";
                } else {
                    // Loop through each row of the result set
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Split the item details into an array
                        $itemDetails = explode('|', $row['itemDetails']);
                        
                        // Start the list
                        $itemList = "<ul>";
                        foreach ($itemDetails as $item) {
                            $itemList .= "<li>$item</li>";
                        }
                        $itemList .= "</ul>";

                        // Split the gcash info into an array
                        $gcashInfo = explode('|', $row['gcashInfo']);
                        $orderID1 = ($row['orderID']);
                        
                        // Start the list
                        $gcash = "<ul>";
                        $orderID2 = '';
                        foreach ($gcashInfo as $item) {
                            if ($orderID1 == $orderID2) {
                                $orderID2 = $orderID1;
                                break;
                            }
                            $gcash .= "<li>$item</li>";
                            $orderID2 = $orderID1;
                        }
                        $gcash .= "</ul>";
                        
                        // Output the table row
                        echo "<tr>
                            <td>";

                            if ($row['orderstatus'] != 'cancelled' && $row['orderstatus'] != 'delivered') {
                            echo "<form method='post' action=''>
                                <input type='hidden' name='orderID' value='{$row['orderID']}'>
                                <input type='hidden' name='newStatus' value='cancelled'>
                                <button type='submit'>Cancel</button>
                            </form>";
                            }

                            echo "</td>
                            <td>{$row['orderID']}</td>
                            <td>{$row['customerName']}</td>
                            <td>{$row['customerAddress']}</td>
                            <td>{$row['customerEmail']}</td>
                            <td>{$row['customerPhoneNumber']}</td>
                            <td>{$row['orderDate']}</td>
                            <td>{$row['orderTotal']}</td>
                            <td>$itemList</td>
                            <td>$gcash</td>
                            <td>{$row['orderstatus']}</td>
                            <td>
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
                                        break;

                        }
                        echo "</form>";
                    }
                    echo "</td></tr>";
                    }

                // Close database connection
                mysqli_close($conn);
            ?>
        </tbody>
    </table>
</body>
</html>
