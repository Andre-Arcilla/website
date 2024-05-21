<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Face Masks</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('images/bg2.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body>
    <?php
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dct";

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $trackingnum = '0928123'; // Example tracking number
        $name = 'ted';
        $address = 'asd2321';
        $email = 'dqwdq@gmaw.com';
        $pnum = '2222312';
        $orderdate = date("Y-m-d");

        // Check if the 'item' key exists in $_POST array
        if (isset($_POST['item'])) {
            $item = $_POST['item'];
            echo "Item: $item";
        }

        echo "<br>";

        // Check if the 'bulk' key exists in $_POST array
        if (isset($_POST['bulk'])) {
            $bulk = $_POST['bulk'];
            echo "Bulk: $bulk";
        } else {
            $bulk = 'N';
            echo "Bulk: $bulk";
        }

        echo "<br>";

        // Check if the 'amount' key exists in $_POST array
        if (isset($_POST['amount'])) {
            $amount = $_POST['amount'];
            echo "Amount: $amount";
        } else {
            echo "Amount not specified";
        }

        echo "<br>";

        // Calculate total price
        $price = $_POST['price'];
        $tprice = $price * $amount;
        echo "Price: $price<br>";
        echo "Total Price: $tprice<br>";

        // Check if the tracking number and item already exist in the database
        $check_sql = "SELECT * FROM order_info oi INNER JOIN order_items oit ON oi.orderID = oit.orderID WHERE oi.trackingNum = '$trackingnum' AND oit.itemName = '$item'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            // If a record with the same tracking number and item exists, cancel the operation
            echo "Order with the same tracking number and item already exists. Canceling operation...";
        } else {
            // If the tracking number and item combination doesn't exist, proceed with insertion
            // Check if the tracking number already exists in the database
            $check_sql = "SELECT orderID FROM order_info WHERE trackingNum = '$trackingnum'";
            $result = $conn->query($check_sql);

            if ($result->num_rows > 0) {
                // If the tracking number exists, fetch the orderID
                $row = $result->fetch_assoc();
                $orderID = $row['orderID'];
            } else {
                // If the tracking number doesn't exist, insert new order info
                $sql_order_info = "INSERT INTO order_info (trackingNum, customerName, orderAddress, orderEmail, orderPNum, orderDate) VALUES ('$trackingnum', '$name', '$address', '$email', '$pnum', '$orderdate')";

                if ($conn->query($sql_order_info) === TRUE) {
                    // Get the auto-generated orderID
                    $orderID = $conn->insert_id;
                } else {
                    echo "Error: " . $sql_order_info . "<br>" . $conn->error;
                }
            }

            // Now, insert data into the order_items table using the obtained orderID
            $sql_order_items = "INSERT INTO order_items (orderID, itemName, bulk, itemAmount, totalPrice) VALUES ('$orderID', '$item', '$bulk', '$amount', '$tprice')";

            // Execute the query
            if ($conn->query($sql_order_items) === TRUE) {
                echo "Order items inserted successfully.";
            } else {
                echo "Error: " . $sql_order_items . "<br>" . $conn->error;
            }
        }

        // Close database connection
        $conn->close();
    ?>
</body>
</html>
