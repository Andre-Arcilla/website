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

// Check if the checkout form was submitted
if (isset($_POST["checkout"])) {
    // Get customer information from the accounts table based on session
    $userID = $_SESSION['userid']; // Assuming you have stored the user's ID in the session
    $customerInfoQuery = "SELECT * FROM accounts WHERE accountID = $userID";
    $customerInfoResult = $conn->query($customerInfoQuery);
    $customerInfo = $customerInfoResult->fetch_assoc();

    // Insert order information into order_info table
    $orderDate = date("Y-m-d");
    $orderTotal = 0; // Initialize order total
    $insertOrderQuery = "INSERT INTO order_info (accountID, orderDate, orderTotal) 
                        VALUES ('$userID', '$orderDate', '$orderTotal')";
    if ($conn->query($insertOrderQuery) === TRUE) {
        $orderID = $conn->insert_id; // Get the last inserted orderID
        foreach ($_SESSION["cart"] as $itemID => $quantity) {
            // Calculate total price for each item and update order total
            $itemPriceQuery = "SELECT itemPrice FROM items WHERE itemID = '$itemID'";
            $itemPriceResult = $conn->query($itemPriceQuery);
            $itemPrice = $itemPriceResult->fetch_assoc()['itemPrice'];
            $totalPrice = $itemPrice * $quantity;
            $orderTotal += $totalPrice;

            // Insert item into order_items table
            $insertItemQuery = "INSERT INTO order_items (orderID, itemID, itemAmount, totalPrice) 
                                VALUES ('$orderID', '$itemID', '$quantity', '$totalPrice')";
            $conn->query($insertItemQuery);
        }

        // Update order total in order_info table
        $updateOrderTotalQuery = "UPDATE order_info SET orderTotal = '$orderTotal' WHERE orderID = '$orderID'";
        $conn->query($updateOrderTotalQuery);

        // Clear the session's cart
        unset($_SESSION["cart"]);

        // Redirect to success page
        header("Location: indexa.php?success=1");
        exit();
    } else {
        echo "Error: " . $insertOrderQuery . "<br>" . $conn->error;
    }
}
?>
