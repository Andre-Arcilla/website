<!DOCTYPE html>
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
            width: 60vw;
            border-collapse: collapse; /* Ensure borders are collapsed into a single border */
            margin-top: 1rem; /* Add margin to separate table from header */
            background-color: lightblue; /* Optional: Change background color to distinguish from table body */
        }

        th, td, #customer {
            padding: .5vw;
            font-size: 1.5vw;
            border: 1px solid black; /* Set border for cells */
        }

        thead {
            position: sticky;
            top: 0; /* Stick the header row to the top of the viewport */
            background-color: deepskyblue; /* Optional: Change background color to distinguish from table body */
        }

        a {
            color: black;
            text-decoration: none !important;
            font-size: 1.5vw;
            margin-bottom: 5rem;
        }
    </style>
</head>
<body>
    <?php
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

        // SQL query to retrieve order information along with customer and item details
        $sql = "SELECT order_info.orderID, 
                        accounts.name AS customerName, 
                        accounts.address AS customerAddress, 
                        accounts.emailaddress AS customerEmail, 
                        accounts.phonenumber AS customerPhoneNumber, 
                        order_info.orderDate, 
                        order_info.orderTotal, 
                        order_info.orderComment, 
                        order_items.itemAmount, 
                        order_items.totalPrice, 
                        items.itemName 
                FROM order_info 
                INNER JOIN order_items ON order_info.orderID = order_items.orderID 
                INNER JOIN accounts ON order_info.accountID = accounts.accountID 
                INNER JOIN items ON order_items.itemID = items.itemID 
                ORDER BY order_info.orderID ASC;";

        // Execute SQL query
        $result = mysqli_query($conn, $sql);
    ?>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Customer Email</th>
                <th>Customer Phone Number</th>
                <th>Order Date</th>
                <th>Order Total</th>
                <th>Item Name</th>
                <th>Item Amount</th>
                <th>Total Price</th>
                <th>Order Comment</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Check if there are any rows returned by the query
                if (mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='11'>No orders yet!</td></tr>";
                } else {
                    // Loop through each row of the result set
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
                            <tr>
                                <td>{$row['orderID']}</td>
                                <td>{$row['customerName']}</td>
                                <td>{$row['customerAddress']}</td>
                                <td>{$row['customerEmail']}</td>
                                <td>{$row['customerPhoneNumber']}</td>
                                <td>{$row['orderDate']}</td>
                                <td>{$row['orderTotal']}</td>
                                <td>{$row['itemName']}</td>
                                <td>{$row['itemAmount']}</td>
                                <td>{$row['totalPrice']}</td>
                                <td>{$row['orderComment']}</td>
                            </tr>
                        ";
                    }
                }

                // Close database connection
                mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <br>

    <a href="adminmain.php">[RETURN]</a>
</body>
</html>
