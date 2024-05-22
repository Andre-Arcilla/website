<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rartest";

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

// Check if tracking number already exists in the order_info table
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
        exit;
    }
}

// Move items from cart to order_items
$sql_cart_items = "SELECT * FROM cart WHERE trackingNum = '$trackingnum'";
$result = $conn->query($sql_cart_items);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $item = $row['itemName'];
        $bulk = $row['bulk'];
        $amount = $row['itemAmount'];
        $price = $row['price'];
        $tprice = $price * $amount;

        $sql_order_items = "INSERT INTO order_items (orderID, itemName, bulk, itemAmount, totalPrice) VALUES ('$orderID', '$item', '$bulk', '$amount', '$tprice')";
        
        if ($conn->query($sql_order_items) === TRUE) {
            // Remove item from cart
            $sql_delete_cart = "DELETE FROM cart WHERE cartID = " . $row['cartID'];
            $conn->query($sql_delete_cart);
        } else {
            echo "Error: " . $sql_order_items . "<br>" . $conn->error;
        }
    }
    echo "Order confirmed and items moved to order_items.";
} else {
    echo "No items in cart to confirm.";
}

// Close database connection
$conn->close();
?>
